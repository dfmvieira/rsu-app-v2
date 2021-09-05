<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                Entity
            </slot>
        </CCardHeader>
        <CCardBody>
            <CForm>
                <CRow>
                    <CCol lg="4" >
                        <CMedia :aside-image-props="{src:entity.logo, height: 200 ,width: 200}"> </CMedia>
                    </CCol>
                    <CCol md="8" >
                        <label><b>Name: </b> {{ entity.name }}</label> <br><br>
                        <label><b>Address: </b> {{ entity.address }}</label> <br><br>
                        <label><b>Phone: </b> {{ entity.phone }}</label> <br><br>
                    </CCol> 
                </CRow>
            </CForm>
        </CCardBody>
    </CCard>
</template>

<script>
import axios from 'axios'
export default {
    name: 'ViennaSigns',
    data () {
        return {
            entities: [],
            editModal: false,
            entity: {
                id: '',
                name: '',
                logo: '',
                address: '',
                phone: '',
                _method:"patch"
            },
            message: '',

        }
    },
    methods: {
        getEntities() {
            axios.get('api/entity' + localStorage.getItem("api_token")).then(response => {
                console.log(response.data)
                this.entities=response.data
                this.entity=this.entities[0]
                console.log(this.entity)
            })
        },
        
        toggleDetails (index) {
            const position = this.details.indexOf(index)
            position !== -1 ? this.details.splice(position, 1) : this.details.push(index)
        },
            
        /* update(entity){
            let self = this;
           axios.put(`api/entity/1` + localStorage.getItem("api_token"), this.entity)
               .then(response=>{
                console.log("sucess")
                self.message = 'Successfully updated Entity.';
                self.showAlert();

                    
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        }, */
        
     
    },
    mounted() {
        this.getEntities();
        
    }
}
</script>