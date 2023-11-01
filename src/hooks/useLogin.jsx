import { useState } from 'react';
const useLogin = () => {
	const [login, setLogin] = useState({
		username: '',
		password: '',
		error: ''
	});
	const setUsername = newUsername => {
		setLogin({
			...login,
			username: newUsername
		});
	};
	const setPassword = newPassword => {
		setLogin({
			...login,
			password: newPassword
		});
	};
	const setError = newError => {
		setLogin({
			...login,
			error: newError
		});
	};
	return {
		...login,
		setUsername,
		setPassword,
		setError
	};
};
export default useLogin;
