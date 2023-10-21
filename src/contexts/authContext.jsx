'use client';
import {
	createContext,
	useCallback,
	useContext,
	useMemo,
	useState
} from 'react';

const AUTH_TOKENS_KEY = 'AUTHENTICATION_FRONTEND';
export const AuthContext = createContext({
	login: data => {},
	logout: () => {},
	isLogged: false,
	authToken: null
});
const AuthContextProvider = ({ children }) => {
	const authTokenInMemory = window.localStorage.getItem(AUTH_TOKENS_KEY);
	const [authToken, setAuthToken] = useState(
		authTokenInMemory === null ? null : JSON.parse(authTokenInMemory)
	);
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
	return useContext(AuthContext);
};
