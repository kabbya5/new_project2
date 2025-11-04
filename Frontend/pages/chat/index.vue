<template> 
    <div class="container mx-auto">
        <div class="p-4 flex justify-between">
            <button class="px-4 py-2 bg-black text-white rounded-md">
                User Name 
            </button>
        </div>

        <div class="border-2 border-slate-200 p-4">
            <div class="relative h-[600px]">
                <div class="absolute bottom-0 left-0 w-full">
                    <form action="#">
                        <div class="flex">
                            <input type="text" name="message" class="w-full border-2 border-slate-300 focus:outline-none">
                            <button type="submit" class="bg-blue-600 text-white w-[100px] p-4 rounded-md"> Send </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

