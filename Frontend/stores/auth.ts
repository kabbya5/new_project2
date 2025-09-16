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
            token.value = newToken;

            console.log('token',newToken);
        }
    };

    const getToken = (): string | null => {
        if (isClient) {
            const tokenData = localStorage.getItem('authToken');
            if (tokenData) {
                const parsedToken = JSON.parse(tokenData);
                
                token.value = parsedToken.value; // Update reactive state
                return parsedToken.value;
               
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

    const getUser =  (): { name: string | null, email: string | null, id: number | null, role: string | null} => {
        if (process.client) {
            try {
                const storedUser = localStorage.getItem('user');
                if (storedUser) {
                    const userData = JSON.parse(storedUser);
                    if(getToken()){
                        return {
                            name: userData.name ?? null,
                            email: userData.email ?? null,
                            id: userData.id ?? null,
                            role: userData.role ?? null,
                        };
                    }
                }
            } catch (error) {
                console.error("Error getting user data:", error);
            }
        }
        return { name: null, email: null, id:null, role:null };
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

    const handleLogout = async (): Promise<void> => {
        try{
            const response = await useApiFetch(`/logout`,{
                method:'POST',
            });

            if (response) {
                clearAuthData();
            }
        } catch(error){
            alert('Logout Faild');
        }
    }

    return {
        token,
        user,
        setToken,
        setUser,
        getToken,
        getUser,
        clearToken,
        clearAuthData,
        handleLogout,
    };
});