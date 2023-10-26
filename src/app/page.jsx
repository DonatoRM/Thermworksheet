'use client';
import { useState } from 'react';
import { useAuthContext } from '@/contexts/authContext';
import { useRouter } from 'next/navigation';

const Home = () => {
	const [username, setUsername] = useState('');
	const [password, setPassword] = useState('');
	const router = useRouter();
	const { login } = useAuthContext();
	const handleLoginSubmit = async event => {
		event.preventDefault();
		const response = await fetch('http://localhost', {
			headers: {
				// Authorization:
				// 	'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdDozMDAwIiwiaWF0IjoxNjk4MzQyMTA0LCJuYmYiOjE2OTgzNDIxMDQsImV4cCI6MTY5ODM0NTcwNCwidXNlcm5hbWUiOiJib3NzIiwicGFzc3dvcmQiOiJib3NzIn0.vODHHejvKSoY32XqKy2NbtaApaLT91zvmgWj-tkhgN8'
				Authorization: 'Basic ' + btoa(username + ':' + password)
			}
		});
		if (!response.ok) {
			return console.log('Error');
		}
		const role = await response.json();
		login(role);
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

export default Home;
