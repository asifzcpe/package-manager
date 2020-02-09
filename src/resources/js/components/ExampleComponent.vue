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
            </div>
        </div>
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
            }
        }
    }
</script>
