import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', () => {

    const isClient = process.client;

    
    const token = ref<string | null>(null);
    const user = ref<object | null>(null);

    if (isClient) {
        const tokenData = localStorage.getItem('authToken');
        if (tokenData) {
            const parsedToken = JSON.parse(tokenData);
            if (parsedToken.expiresAt > Date.now()) {
                token.value = parsedToken.value;
            } else {
                localStorage.removeItem('authToken');
            }
        }

        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            user.value = JSON.parse(storedUser);
        }
    }

    const setToken = (newToken: string): void => {
        if (isClient) {
            const expirationTime = Date.now() + 2 * 24 * 60 * 60 * 1000; // 2 days
            const tokenData = {
                value: newToken,
                expiresAt: expirationTime,
            };

            localStorage.setItem('authToken', JSON.stringify(tokenData));
            token.value = newToken; // Sync with reactive state
        }
    };

    const getToken = (): string | null => {
        if (isClient) {
            const tokenData = localStorage.getItem('authToken');
            if (tokenData) {
                const parsedToken = JSON.parse(tokenData);
                if (parsedToken.expiresAt > Date.now()) {
                    token.value = parsedToken.value; // Update reactive state
                    return parsedToken.value;
                } else {
                    clearToken(); // Token expired
                }
            }
        }
        return null;
    };

    const setUser = (newUser: object): void => {
        if (isClient) {
            localStorage.setItem('user', JSON.stringify(newUser));
            user.value = newUser; 
        }
    };

    const getUser = (): Promise<{ name: string | null, email: string | null, id:number|null }> => {
        if (process.client) {
            try {
                const storedUser = localStorage.getItem('user');
                if (storedUser) {
                    const userData = JSON.parse(storedUser);
                    return {
                        name: userData.name ?? null,
                        email: userData.email ?? null,
                        id:userData.id ?? null,
                    };
                }
            } catch (error) {
                console.error("Error getting user data:", error);
            }
        }
        return { name: null, email: null,id:null };
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
        token,
        user,
        setToken,
        setUser,
        getToken,
        getUser,
        clearToken,
        clearAuthData,
    };
});
