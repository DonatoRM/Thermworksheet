'use client';
import { useAuthContext } from '@/contexts/authContext';
import { redirect } from 'next/navigation';
const Layout = ({ children }) => {
	const { isLogged, getRole } = useAuthContext();
	if (!isLogged || getRole() === 2) {
		redirect('/');
		return null;
	}
	return <div>{children}</div>;
};
export default Layout;
