import style from './PageSelector.module.css';
import IconButton from '../buttons/IconButton';
import ArrowLeftButton from '../icons/ArrowLeftIcon';
import ArrowRightButton from '../icons/ArrowRightIcon';
const PageSelector = ({ page, setPage, totalPages }) => {
	const isFirstPage = page === 1;
	const isLastPage = page === totalPages || totalPages === 0;
	return (
		<div className={style.wrapper}>
			<IconButton
				filled
				disabled={isFirstPage}
				icon={ArrowLeftButton}
				onClick={() => setPage(page - 1)}
			/>
			<span>
				Página {page} de {totalPages || 1}
			</span>
			<IconButton
				filled
				disabled={isLastPage}
				icon={ArrowRightButton}
				onClick={() => setPage(page + 1)}
			/>
		</div>
	);
};
export default PageSelector;
