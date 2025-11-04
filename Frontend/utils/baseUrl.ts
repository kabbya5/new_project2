export function getBaseUrl(): string {
    const config = useRuntimeConfig();
    const baseUrl = config.public.baseURL;

    return `${baseUrl}/api`;
}
