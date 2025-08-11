export interface GameForm{
    english_name:string,
    bangla_name:string,
    hindi_name:string,
    image: File | null;
    position:null |number;
    categories: number[];
    provider_id: number;
}