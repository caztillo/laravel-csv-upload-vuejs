<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 offset-sm-3 mt-5">
                <div class="card mb-5">
                    <div class="text-center text-white  card-header bg-primary ">
                        File Upload
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submitFile()" ref="uploadform">
                            <div class="form-group">
                                <label for="file"><b>Select a  CSV file:</b></label>
                                
                                <input type="file" class="d-block mt-2" ref="file" name="csv_file" @change="onFileChange" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" :disabled="isLoading">{{ isLoading ? 'Loading...':'Upload' }}</button>
                            </div>
                        </form>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" 
                                :style="{width: progressBar + '%'}" 
                                :aria-valuenow="progressBar" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            progressBar: 0,
            isLoading: false,
            file: ''
        };
    },
    methods: {
        onFileChange(e) 
        {
         this.file = e.target.files[0]
        },
        submitFile() 
        {
            this.isLoading = true;
            let formData = new FormData();
            formData.append('csv_file', this.file);
           
            axios.post('/api/csv-upload/', formData, {
                onUploadProgress: function( progressEvent ) 
                    {
                        this.progressBar = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total));
                    }.bind(this)
                })
                .then(function (response) {
                    this.reset();

                    toast.fire({
                      icon: response.data.status,
                      title: response.data.message
                    })
                    

                }.bind(this))
                .catch(function (error) {
                    this.reset();
                    toast.fire({
                      icon: 'error',
                      text: error.response.data.message,
                      title: 'Oops!'
                    })

                }.bind(this));
            
        },
        reset()
        {
            this.$refs.file.value = '';   
            this.isLoading = false;
            this.progressBar = 0;     
        },
    }
}
</script>