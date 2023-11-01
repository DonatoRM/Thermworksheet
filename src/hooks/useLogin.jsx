import { useState } from 'react';
const useLogin = () => {
	const [login, setLogin] = useState({
		username: '',
		password: ''
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
	return {
		...login,
		setUsername,
		setPassword
	};
};
export default useLogin;
