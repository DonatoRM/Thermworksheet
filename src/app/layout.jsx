import { Inter, Poppins } from 'next/font/google';
import '../styles/globals.css';
import AuthContextProvider from '@/contexts/authContext';

const poppins = Poppins({
	weight: ['200', '400', '600', '800'],
	subsets: ['latin']
});

export const metadata = {
	title: 'Thermworksheet',
	description: 'Proyecto de DAW',
	creator: 'Donato Ramos Martínez',
	keywords: ['thermographic', 'project', 'daw']
};
const RootLayout = ({ children }) => {
	return (
		<html lang='es'>
			<body className={poppins.className}>
				<AuthContextProvider>{children}</AuthContextProvider>
			</body>
		</html>
	);
};
export default RootLayout;
