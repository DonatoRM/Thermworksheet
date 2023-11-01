'use client';
import { useAuthContext } from '@/contexts/authContext';
import { redirect } from 'next/navigation';
import { Fragment } from 'react';
import Navigator from '@/components/pageParts/navigator';
const Layout = ({ children }) => {
	const { isLogged, getRole } = useAuthContext();
	if (!isLogged || getRole() === 2) {
		redirect('/');
		return null;
	}
	return (
		<Fragment>
			<Navigator />
			<div>{children}</div>
		</Fragment>
	);
};
export default Layout;
