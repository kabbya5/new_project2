import {defineStore} from 'pinia';

export const useToken = defineStore('token', () =>{
    const isClient = process.client;

    const token = ref<string |null> (isClient ? localStorage.getItem('authToken') : null)
    const user = ref<any | null> (isClient && localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')!): null);

    const setToken = (newToken:string):void =>{
        if(isClient){
            const expirationTime = Date.now() + 2 * 24 * 60 * 1000;

            const tokenData = {
                value: newToken,
                expiresAt:expirationTime,
            }

            localStorage.setItem('authToken', JSON.stringify(tokenData));
        }
    }

    const setUser = (user:object):void =>{
        if(isClient){
            localStorage.setItem('user', JSON.stringify(user));
        }
    }

    const getUser = (): object|null => {
        return user.value ?? null;
    }
    const getToken = (): string | null => {
        if (isClient) {
            const tokenData = localStorage.getItem('authToken');
            
            if (!tokenData) {
                return null;
            }

            const parsedToken = JSON.parse(tokenData);
            if (parsedToken.expiresAt < Date.now()) {
                clearToken();
                return null;
            }

            return parsedToken.value;
        }
        return null; 
    };

   
    const clearToken = (): void => {
        if (isClient) {
            token.value = null;
            localStorage.removeItem('authToken');  
        }
    };


    const clearAuthData = (): void => {
        if (isClient) {
            user.value = null;
            clearToken();
            localStorage.removeItem('user');  
        }
    };

    return {
        setToken,
        setUser,
        getToken,
        getUser,
        clearAuthData,
    };
});