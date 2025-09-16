export interface GameForm{
    game_code:string,
    image_url:string,
    english_name:string,
    bangla_name:string,
    hindi_name:string,
    image: File | null;
    position:null |number;
    categories: number[];
    provider_id: number;
}