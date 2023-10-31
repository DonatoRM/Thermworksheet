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
export const AuthContext = createContext({
	login: data => {},
	logout: () => {},
	isLogged: false,
	authToken: null
});
const AuthContextProvider = ({ children }) => {
	const [authTokenInMemory, setAuthTokenInMemory] = useState(null);
	useEffect(() => {
		const authTokenInMemory = localStorage.getItem(AUTH_TOKENS_KEY);
		setAuthTokenInMemory(authTokenInMemory);
	}, [authTokenInMemory]);
	const [authToken, setAuthToken] = useState(() => {
		return authTokenInMemory === null ? null : JSON.parse(authTokenInMemory);
	});
	const login = useCallback(function (authToken) {
		window.localStorage.setItem(AUTH_TOKENS_KEY, JSON.stringify(authToken));
		setAuthToken(authToken);
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
			isLogged: authToken != null
		}),
		[authToken, login, logout]
	);
	return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};
export default AuthContextProvider;
export const useAuthContext = () => {
	const context = useContext(AuthContext);
	if (!context) throw new Error('useTasks must used within a provider');
	return context;
};
