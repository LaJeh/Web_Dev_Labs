import type { PokemonSummary } from "@/PokeAPI/pokemon";

type PokemonDisplayProps = {
  trimmedQuery: string;
  pokemon: PokemonSummary | null;
};

export function PokemonDisplay({ trimmedQuery, pokemon }: PokemonDisplayProps) {
  if (!trimmedQuery) {
    return <p>Type a Pokémon name or number 67.</p>;
  }

  if (!pokemon) {
    return <p>Di ko mahanap yung pokemon lodi.</p>;
  }

  const heightInMeters = pokemon.height / 10;
  const weightInKg = pokemon.weight / 10;

  let imageUrl = pokemon.sprites.front_default;
  const officialArt = pokemon.sprites.other?.["official-artwork"];
  if (officialArt?.front_default) {
    imageUrl = officialArt.front_default;
  }

  return (
    <div className="result">
      {imageUrl ? (
        <img src={imageUrl} alt={pokemon.name} width={200} height={200} />
      ) : (
        <p>Walang picture ng pokemon lodi.</p>
      )}
      <p>
        <b>Name:</b> {pokemon.name}
      </p>
      <p>
        <b>Height:</b> {heightInMeters} m
      </p>
      <p>
        <b>Weight:</b> {weightInKg} kg
      </p>
    </div>
  );
}
