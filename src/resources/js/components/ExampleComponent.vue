<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <input type="search"
                       placeholder="What're you searching for?"
                       aria-describedby="button-addon"
                       class="form-control border-primary"/>
            </div>
            <div class="col-md-3">
                <input type="button" @click="next(pageLink)" class="btn btn-info" value="Previous"/>
                <input type="button" @click="next(pageLink)" class="btn btn-info" value="Next"/>
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" @click="install" data-target="#exampleModal">Install</button>
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" @click="stop" data-target="#exampleModal">Stop</button>
            </div>
        </div>
        <br>
        <div class="tableFixHead">
            <table class="table table-sm">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Downloads</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(pack,index) in packages" :key="index">
                    <td>{{index+1}}</td>
                    <td>{{pack.name}}</td>
                    <td>{{pack.description}}</td>
                    <td>{{pack.downloads}}</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="actions">
                            <button type="button" class="btn btn-success btn-sm">Install</button>
                            <button type="button" disabled class="btn btn-danger btn-sm">Uninstall</button>
                            <button type="button" class="btn btn-secondary btn-sm">Details</button>
                        </div>

                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .tableFixHead {
        overflow-y: auto;
        height: 500px;
        width: 100%;
    }

    .tableFixHead thead th {
        position: sticky;
        top: 0;
        z-index: 9;
    }

    /* Just common table stuff. Really. */
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        padding: 3px 14px;
    }

    th {
        background: #f28d1a;
    }

</style>
<script>
    export default {
        data() {
            return {
                packages: [],
                totalResults: 0,
                pageLink: '',
                eventSource:null,
            }
        },
        created() {
            axios.get('https://packagist.org/search.json?q=laravel-moduler').then((response) => {
                console.log(response);
                let data = response.data;
                this.packages = data.results;
                this.totalResults = data.total;
                this.pageLink = data.next;
            }).catch((err) => {
                console.log("no onternet");
            });
        },
        methods: {
            next(nextLink) {
                axios.get(nextLink).then((response) => {
                    console.log(response);
                    let data = response.data;
                    this.packages = data.results;
                    this.totalResults = data.total;
                    this.pageLink = data.next;
                })
            },
            install(){
                // axios.get('http://localhost:1000/run').then((response) => {
                //     console.log(response);
                // })
                this.eventSource = new EventSource('http://localhost:9000/run');

                this.eventSource.onmessage = function(e) {
                    console.log(e.data);
                }

                this.eventSource.addEventListener('process_ended',(e)=>{
                    this.eventSource.close();
                });
            },
            stop(){

            }
        }
    }
</script>
