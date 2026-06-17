import {
  type ComponentPropsWithoutRef,
  type CSSProperties,
  type ReactElement,
  type SyntheticEvent,
} from 'react';

interface SearchFormProps
  extends Omit<ComponentPropsWithoutRef<'input'>, 'className' | 'style'> {
  readonly className?: string;
  readonly style?: CSSProperties;
}

function SearchForm({
  className = '',
  style,
  ...props
}: SearchFormProps): ReactElement {
  function handleSubmit(event: SyntheticEvent<HTMLFormElement>): void {
    event.preventDefault();
  }

  return (
    <form action="#" onSubmit={handleSubmit}>
      <div className={`search-box ${className}`} style={style} role="search">
        <span aria-hidden="true">⌕</span>
        <input type="search" {...props} />
      </div>
    </form>
  );
}

export default SearchForm;
