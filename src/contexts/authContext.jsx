'use client';
import {
	createContext,
	useCallback,
	useContext,
	useEffect,
	useMemo,
	useState
} from 'react';

const AUTH_TOKENS_KEY = 'authToken';
const AUTH_TOKENS_ROLE = 'role';
export const AuthContext = createContext({
	login: data => {},
	logout: () => {},
	isLogged: false,
	authToken: null,
	addRole: role => {},
	getRole: () => {}
});
const AuthContextProvider = ({ children }) => {
	const [authToken, setAuthToken] = useState(undefined);
	useEffect(() => {
		const authTokenInMemory = window.localStorage.getItem(AUTH_TOKENS_KEY);
		const parsedUser =
			authTokenInMemory === null ? null : JSON.parse(authTokenInMemory);
		setTimeout(() => {
			setAuthToken(parsedUser);
		}, 500);
	}, []);
	const login = useCallback(function (authToken) {
		window.localStorage.setItem(AUTH_TOKENS_KEY, JSON.stringify(authToken));
		setAuthToken(authToken);
	}, []);
	const addRole = useCallback(function (role) {
		window.localStorage.setItem(AUTH_TOKENS_ROLE, JSON.stringify(role));
		setTimeout(() => {}, 500);
	}, []);
	const getRole = useCallback(function () {
		return JSON.parse(window.localStorage.getItem(AUTH_TOKENS_ROLE));
	}, []);
	const logout = useCallback(function () {
		window.localStorage.removeItem(AUTH_TOKENS_KEY);
		setAuthToken(null);
	}, []);
	const value = useMemo(
		() => ({
			login,
			logout,
			authToken,
			isLogged: authToken != null,
			addRole,
			getRole
		}),
		[authToken, login, logout, addRole, getRole]
	);
	return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};
export default AuthContextProvider;
export const useAuthContext = () => {
	const context = useContext(AuthContext);
	if (!context) throw new Error('useTasks must used within a provider');
	return context;
};
