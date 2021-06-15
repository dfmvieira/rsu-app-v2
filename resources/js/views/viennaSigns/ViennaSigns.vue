<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                <CIcon name="cil-grid"/> {{caption}}
            </slot>
        </CCardHeader>
    </CCard>
</template>

<script>
import axios from 'axios'
export default {
    name: 'Vienna Signs',
    props: {
        items: Array,
        fields: {
            type: Array,
            default () {
                ['Name', 'Image', 'Category'] 
            }
        }
    },
    methods: {
        getViennaSigns() {
            axios.get('api/vienna').then(response => {
                return response.data
            })
        },
        getViennaCategories() {
            axios.get('api/vienna/signscategories').then(response => {
                return response.data
            })
        }
    },
    mounted() {
        this.getViennaCategories()
    }
}
</script>