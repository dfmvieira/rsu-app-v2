<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                Entity
            </slot>
        </CCardHeader>
        <CCardBody>
            <CAlert
              :show.sync="dismissCountDown"
              color="success"
              fade
            >
              ({{dismissCountDown}}) {{ message }}
            </CAlert>
            <CForm>
                <CRow>
                    <CCol lg="4" >
                        <CMedia :aside-image-props="{src:entity.logo, height: 200 ,width: 200}"> </CMedia>
                    </CCol>
                    <CCol md="8" >
                       <CInput
                            label="Name"
                            horizontal
                            :lazy="false"
                            :value.sync="entity.name"
                            
                            
                            invalidFeedback="This is a required field and must be at least 1 character"
                        />
                        <CInput
                            label="Address"
                            horizontal
                            :lazy="false"
                            :value.sync="entity.address"
                       
                           
                            invalidFeedback="This is a required field and must be at least 1 character"
                        />
                        <CInput
                            label="Phone"
                            horizontal
                            :lazy="false"
                            :value.sync="entity.phone"
                          
                           
                            invalidFeedback="This is a required field and must be at least 1 character"
                        /> 
                        <CButton
                            @click="update()"
                            size="sm" 
                            color="primary"
                            >
                            Guardar
                        </CButton>
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
            showMessage: false,
            message: '',
            dismissSecs: 7,
            dismissCountDown: 0,
            showDismissibleAlert: false,

        }
    },
    methods: {
        getEntities() {
            axios.get('api/entity').then(response => {
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
            
        update(entity){
            let self = this;
           axios.put(`api/entity/1`, this.entity)
               .then(response=>{
                console.log("sucess")
                self.message = 'Successfully updated Entity.';
                self.showAlert();
               /*  this.loadViennaSigns() */

                    
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
 /*        async loadViennaSigns() {
            this.signs = await this.getEntities();
            this.viennaSign = await this.getEmptyForm();
            await this.$nextTick() // waits for the next event tick before completeing function.
        }, */

        countDownChanged (dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
        showAlert () {
            this.dismissCountDown = this.dismissSecs
        },
     
    },
    mounted() {
        this.getEntities();
        
    }
}
</script>