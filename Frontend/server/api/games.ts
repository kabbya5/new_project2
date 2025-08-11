export default defineEventHandler( async () => {
    await new Promise(resolve => setTimeout(resolve, 100))
    return [
        {
            id: 1,
            name: 'Supper Ace',
            image: '/images/supper-ace.png',
            category: 'Slot',
            provider: {
                name: 'jili',
                logo: '/image/roletee.png'
            },
            link: '/games/lili'
        },
        {
            id: 2,
            name: '7 Up Down',
            image: '/images/7_up_down.png',
            category: 'Slot',
            provider: {
                name: 'jili',
                logo: '/images/roulette.png'
            },
            link: '/games/slot'
        },
        {
            id: 3,
            name: 'Supper Ace Delux',
            image: '/images/super_ace_delux.jpg',
            category: 'Slot',
            provider: {
                name: 'JILI',
                logo: '/providers/evolution.png'
            },
            link: '/games/poker'
        },
        {
            id: 4,
            name: 'Money Comming',
            image: '/images/money-comming.jpg',
            category: 'Slot',
            provider: {
                name: 'Jili',
                logo: '/providers/ezugi.png'
            },
            link: '/games/roulette'
        },
        {
            id: 5,
            name: 'Fortune Gems',
            image: '/images/fortune_geems.jpg',
            category: 'Slot',
            provider: {
                name: 'Jili',
                logo: '/providers/ezugi.png'
            },
            link: '/games/roulette'
        },
        {
            id: 6,
            name: 'Money Party',
            image: '/images/money-comming_j.jpg',
            category: 'Slot',
            provider: {
                name: 'Jili',
                logo: '/providers/ezugi.png'
            },
            link: '/games/roulette'
        },
        {
            id: 6,
            name: 'Dragon Tiger',
            image: '/images/drogon_tiger.png',
            category: 'Slot',
            provider: {
                name: 'Jili',
                logo: '/providers/ezugi.png'
            },
            link: '/games/roulette'
        },
        {
            id: 7,
            name: 'money comming 2',
            image: '/images/money_comming-2.jpg',
            category: 'Slot',
            provider: {
                name: 'Jili',
                logo: '/providers/ezugi.png'
            },
            link: '/games/roulette'
        },
        {
            id: 8,
            name: 'Dice',
            image: '/images/dice.png',
            category: 'Slot',
            provider: {
                name: 'Jili',
                logo: '/providers/ezugi.png'
            },
            link: '/games/roulette'
        },

        {
            id: 6,
            name: 'Lucky Number',
            image: '/images/lucky_number.png',
            category: 'Slot',
            provider: {
                name: 'Jili',
                logo: '/providers/ezugi.png'
            },
            link: '/games/roulette'
        },
        {
            id: 7,
            name: 'Fortune gems 2',
            image: '/images/fortune_gems_2.jpg',
            category: 'Slot',
            provider: {
                name: 'Jili',
                logo: '/providers/ezugi.png'
            },
            link: '/games/roulette'
        }
    ]
})