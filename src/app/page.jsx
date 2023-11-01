'use client';
import style from './page.module.css';
import { useAuthContext } from '@/contexts/authContext';
import { useRouter } from 'next/navigation';
import useLogin from '@/hooks/useLogin';
import InputText from '@/components/forms/InputText';
import Button from '@/components/buttons/Button';

const Home = () => {
	const { username, password, setUsername, setPassword } = useLogin();
	const router = useRouter();
	const { login, isLogged, authToken, addRole } = useAuthContext();
	let authWithType = 'Basic ' + btoa(username + ':' + password);
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
		addRole(objAuthentication.role);
		const roleUser = objAuthentication.role;
		switch (roleUser) {
			case 1:
			case 3:
				return router.push('/data');
			case 2:
				return router.push('/view');
			default:
				throw new Error('Tipo de usuario no registrado');
		}
	};
	const tokenSubmit = async authToken => {
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
		addRole(objAuthentication.role);
		const roleUser = objAuthentication.role;
		switch (roleUser) {
			case 1:
			case 3:
				return router.push('/data');
			case 2:
				return router.push('/view');
			default:
				throw new Error('Tipo de usuario no registrado');
		}
	};
	if (isLogged) {
		tokenSubmit(authToken);
	}
	return (
		<main className={style.main}>
			<section>
				<form onSubmit={handleLoginSubmit} className={style.form}>
					<header>
						<h2 className={style.title}>Login</h2>
					</header>
					<div className={style.row}>
						<InputText
							label='usuario'
							id='username'
							placeholder='usuario'
							onChange={event => setUsername(event.target.value)}
							required
						/>
					</div>
					<div className={style.row}>
						<InputText
							label='contraseña'
							id='password'
							inputType='password'
							placeholder='contraseña'
							onChange={event => setPassword(event.target.value)}
							required
						/>
					</div>
					<div className={style.button}>
						<Button kind='primary' id='submit' className={style.button}>
							Aceptar
						</Button>
					</div>
				</form>
			</section>
		</main>
	);
};
export default Home;
