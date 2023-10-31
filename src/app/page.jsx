'use client';
import { useState } from 'react';
import { useAuthContext } from '@/contexts/authContext';
import { useRouter } from 'next/navigation';

const Home = () => {
	const { username, password, setUsername, setPassword } = useLogin();
	const router = useRouter();
	const { login, isLogged, authToken } = useAuthContext();
	let authWithType = 'Basic ' + btoa(username + ':' + password);
	console.log(isLogged, authToken);
	if (isLogged) {
		authWithType = 'Bearer ' + authToken;
	}
	const handleLoginSubmit = async event => {
		event.preventDefault();
		const response = await fetch('http://localhost', {
			headers: {
				Authorization: authWithType
			}
		});
		if (!response.ok) {
			return console.log('Error');
		}
		const objAuthentication = await response.json();
		login(objAuthentication.authToken);
		// todo: controlar la página que se abrirá según sea el role del usuario validado. suponemos inicialmente que es 3 (boss)
		return router.push('/data');
	};
	return (
		<main>
			<section>
				<form onSubmit={handleLoginSubmit}>
					<div>
						<label htmlFor='username'>Usuario: </label>
						<input
							type='text'
							name='username'
							id='username'
							placeholder='usuario'
							onChange={event => setUsername(event.target.value)}
							required
						/>
					</div>
					<div>
						<label htmlFor='password'>Contraseña: </label>
						<input
							type='password'
							name='password'
							id='password'
							placeholder='contraseña'
							onChange={event => setPassword(event.target.value)}
							required
						/>
					</div>
					<button id='submit' name='submit'>
						Aceptar
					</button>
				</form>
			</section>
		</main>
	);
};

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
export default Home;
