import { Inter } from 'next/font/google';
import '../styles/globals.css';
import AuthContextProvider from '@/contexts/authContext';

const inter = Inter({ subsets: ['latin'] });

export const metadata = {
	title: 'Thermworksheet',
	description: 'Proyecto de DAW',
	creator: 'Donato Ramos Martínez',
	keywords: ['thermographic', 'project', 'daw']
};
const RootLayout = ({ children }) => {
	return (
		<html lang='es'>
			<body className={inter.className}>
				<AuthContextProvider>{children}</AuthContextProvider>
			</body>
		</html>
	);
};
export default RootLayout;
