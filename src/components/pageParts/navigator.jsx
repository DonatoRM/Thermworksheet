import style from './navigator.module.css';
import InputSearch from '@/components/forms/InputSearch';
import Button from '@/components/buttons/Button';
const Navigator = () => {
	return (
		<nav className={style.nav}>
			<div className={style.options}>
				<Button kind='primary'>Crear Cliente</Button>
				<Button kind='primary'>Crear Instalación</Button>
				<Button kind='primary'>Crear Localización</Button>
				<Button kind='primary'>Crear Posición</Button>
			</div>
			<div className={style.search}>
				<InputSearch className={style.search} />
				<Button className={style.exit} kind='primary'>
					Cerrar Sesión
				</Button>
			</div>
		</nav>
	);
};
export default Navigator;
