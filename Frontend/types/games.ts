
import type { Provider } from "./provider";
import type { Category } from "./category";

export interface Game {
  id: number;
  game_code: string;
  english_name: string;
  bangla_name: string;
  hindi_name: string;
  thumbnail: string;
  categories: Category | Category[];
  popularity: number;
  position: number | null;
  provider: Provider | Provider;
  url: string | null;
  image_url:string|null;
}