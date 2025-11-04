export default function getFileUrl(path:string){
    const baseUrl = useRuntimeConfig().public.baseURL;
    return `${baseUrl}/${path}`;
}