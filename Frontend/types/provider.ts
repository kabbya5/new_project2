import type { Category } from "./category";

export interface Provider{
    id:number,
    english_name:string,
    bangla_name:string,
    hindi_name:string,
    position:number,
    logo:string,
    'slug':string,
    games?:number|null,
    categories?:Category[];
}