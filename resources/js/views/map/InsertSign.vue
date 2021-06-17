<template>
    
</template>

<<script>
import axios from 'axios'
export default {
    name: 'InsertSign',
    data: () => {
        return {
            name: 'Insert Sign',
            IvisignMap: {
                name: '',
                ViennaID: null,
                latitude: 0,
                longitude: 0,
                status: null,
            },

        }
        
    },
    methods: {
        getViennaSigns() {
            axios.get('api/viennaSigns/getAll').then(response => {
                response.data.forEach(item => {
                    
                })
            })
        }
    }
}
</script>>