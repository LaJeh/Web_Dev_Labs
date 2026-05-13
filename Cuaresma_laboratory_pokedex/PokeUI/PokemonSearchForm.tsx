type PokemonSearchFormProps = {
  defaultQuery: string;
};

export function PokemonSearchForm({ defaultQuery }: PokemonSearchFormProps) {
  return (
    <form method="get" action="/" name="pokemon-search-form">
      <input
        type="search"
        name="q"
        placeholder="pikachu or 25"
        defaultValue={defaultQuery}
      />{" "}
      <button type="submit">Search</button>
    </form>
  );
}
