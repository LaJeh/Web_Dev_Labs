// Simple pokedex - search by name or number (pokeapi)

async function loadPokemon(userInput: string) {
  const slug = userInput
    .trim()
    .toLowerCase()
    .replace(/\s+/g, "-")
    .replace(/[^a-z0-9-]/g, "");
  if (!slug) return null;

  try {
    const res = await fetch(
      `https://pokeapi.co/api/v2/pokemon/${encodeURIComponent(slug)}`,
    );
    if (res.status === 404) return null;
    if (!res.ok) return null;
    return (await res.json()) as {
      name: string;
      height: number;
      weight: number;
      sprites: {
        front_default: string | null;
        other?: { "official-artwork"?: { front_default: string | null } };
      };
    };
  } catch {
    return null;
  }
}

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
  const pokemon = trimmed ? await loadPokemon(q) : null;

  const pic =
    pokemon?.sprites.other?.["official-artwork"]?.front_default ??
    pokemon?.sprites.front_default ??
    null;

  return (
    <main>
      <h1>My Pokedex</h1>
      <form method="get" action="/" name="pokemon-search-form">
        <input
          type="search"
          name="q"
          placeholder="pikachu or 25"
          defaultValue={q}
        />{" "}
        <button type="submit">Search</button>
      </form>

      {!trimmed && <p>Try searching for a pokemon name.</p>}

      {trimmed && !pokemon && <p>Could not find that pokemon.</p>}

      {pokemon && pic && (
        <div className="result">
          <img src={pic} alt={pokemon.name} width={200} height={200} />
          <p>
            <b>Name:</b> {pokemon.name}
          </p>
          <p>
            <b>Height:</b> {pokemon.height / 10} m
          </p>
          <p>
            <b>Weight:</b> {pokemon.weight / 10} kg
          </p>
        </div>
      )}

      {pokemon && !pic && (
        <div className="result">
          <p>
            <b>Name:</b> {pokemon.name}
          </p>
          <p>No picture for this one.</p>
          <p>
            <b>Height:</b> {pokemon.height / 10} m
          </p>
          <p>
            <b>Weight:</b> {pokemon.weight / 10} kg
          </p>
        </div>
      )}
    </main>
  );
}
