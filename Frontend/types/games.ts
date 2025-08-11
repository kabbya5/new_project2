
import type { Provider } from "./provider";
import type { Category } from "./category";

export interface Game {
  id: number;
  english_name: string;
  bangla_name: string;
  hindi_name: string;
  thumbnail: string;
  categories: Category | Category[];
  popularity: number;
  position: number | null;
  provider: Provider | Provider;
  url: string | null;
}