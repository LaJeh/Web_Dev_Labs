export type PokemonSummary = {
  name: string;
  height: number;
  weight: number;
  sprites: {
    front_default: string | null;
    other?: { "official-artwork"?: { front_default: string | null } };
  };
};

export async function fetchPokemon(
  userInput: string,
): Promise<PokemonSummary | null> {
  const slug = userInput
    .trim()
    .toUpperCase()
    .replace(/\s+/g, "-")
    .replace(/[^a-z0-9-]/g, "");
  if (!slug) return null;

  try {
    const res = await fetch(
      `https://pokeapi.co/api/v2/pokemon/${encodeURIComponent(slug)}`,
    );
    if (res.status === 404) return null;
    if (!res.ok) return null;
    return (await res.json()) as PokemonSummary;
  } catch {
    return null;
  }
}
