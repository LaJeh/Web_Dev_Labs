import { fetchPokemon } from "@/PokeAPI/pokemon";
import { PokemonDisplay } from "@/PokeUI/PokemonDisplay";
import { PokemonSearchForm } from "@/PokeUI/PokemonSearchForm";

export default async function Page({
  searchParams,
}: {
  searchParams: Promise<{ q?: string | string[] }>;
}) {
  const sp = await searchParams;
  let q = "";
  if (typeof sp.q === "string") q = sp.q;
  else if (Array.isArray(sp.q)) q = sp.q[0] ?? "";

  const trimmed = q.trim();
  const pokemon = trimmed ? await fetchPokemon(q) : null;

  return (
    <main>
      <h1>My Pokedex</h1>
      <PokemonSearchForm defaultQuery={q} />
      <PokemonDisplay trimmedQuery={trimmed} pokemon={pokemon} />
    </main>
  );
}
