export interface ProviderForm{
    provider_id:null|number,
    english_name:string,
    bangla_name:string,
    hindi_name:string,
    image: null | File,
    position:null |number,
    categories: number[],
}