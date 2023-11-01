import style from './InputText.module.css';
const InputText = ({ label, error, inputType, className, ...props }) => {
	let type = 'text';
	if (inputType === 'password') type = 'password';
	return (
		<label className={className}>
			<span className={style.label}>{label}</span>
			<input
				{...props}
				className={`${style.input} ${error ? style.borderError : ''}`}
				type={type}
			></input>
			{error && <span className={style.error}>{error}</span>}
		</label>
	);
};
export default InputText;
