import type { Provider } from "./provider";
import type { Game } from "./games";

export interface Category{
    id:number,
    english_name:string,
    bangla_name:string,
    hindi_name:string,
    position:number,
    image_url:string,
    'slug':string,
    games?:Game[],
    providers?:Provider[];
}