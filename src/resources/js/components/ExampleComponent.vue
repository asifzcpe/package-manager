<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <input type="search"
                       placeholder="What're you searching for?"
                       aria-describedby="button-addon"
                       v-model="packageSearch"
                       @keyup.enter="search"
                       class="form-control border-primary"/>
            </div>
            <div class="col-md-3">
                <input type="button" @click="navigatePage(previousPageLink)" class="btn btn-info" value="Previous"/>
                <input type="button" @click="navigatePage(nextPageLink)" class="btn btn-info" value="Next"/>
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
                <tr v-if="!packages.length || showLoading">
                    <td colspan="5" class="text-center">
                        Packages are getting loaded
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </td>
                </tr>
                <tr v-for="(pack,index) in packages" :key="index">
                    <td>{{index+1}}</td>
                    <td>{{pack.name}}</td>
                    <td>{{pack.description}}</td>
                    <td>{{pack.downloads}}</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="actions">
                            <button type="button"
                                    class="btn btn-success btn-sm"
                                    @click="openPackageInstallationModal(pack.name)"
                                    data-toggle="modal"
                                    data-target="#exampleModal">Install
                            </button>
                            <button type="button" disabled class="btn btn-danger btn-sm">Uninstall</button>
                            <button type="button" class="btn btn-secondary btn-sm">Details</button>
                        </div>

                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #f28d1a;">
                        <h5 class="modal-title" id="exampleModalLabel">Package Installation Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="well profile">
                                    <div class="col-sm-12">
                                        <div class="col-md-12">
                                            <h2>{{ packageInfo.name }}</h2>
                                            <p><strong>Description: </strong> {{ packageInfo.description }}</p>
                                            <p><strong>Type: </strong> {{ packageInfo.type }} </p>
                                            <p><strong>Repository: </strong>
                                                <a target="_blank"
                                                   :href="packageInfo.repository">{{ packageInfo.repository }}</a></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">GitHub stars: {{
                                                    packageInfo.github_stars }}
                                                </div>
                                            </div>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Watcher: {{
                                                    packageInfo.github_watchers }}
                                                </div>
                                            </div>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Forks: {{
                                                    packageInfo.github_forks }}
                                                </div>
                                            </div>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Open Issues: {{ packageInfo.github_open_issues }}
                                                </div>
                                            </div>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Total: {{ downloads.total }}
                                                </div>
                                            </div>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Monthly: {{ downloads.monthly }}
                                                </div>
                                            </div>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Daily: {{ downloads.daily }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 overflow-auto"
                                     style="height:200px; background-color:black;color:white;">
                                    <p id="result"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="button"
                                class="btn btn-primary"
                                @click="installPackageConfirmation(packageInfo.name)">{{ installButtonText }}
                        </button>
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
                nextPageLink: '',
                eventSource: null,
                packageInfo: [],
                downloads: 0,
                composerOutput: '',
                packageSearch: '',
                showLoading:false,
                previousPageLink:'',
                installButtonText:'Install Package',
            }
        },
        created() {
            axios.get('https://packagist.org/search.json?q=laravel-moduler').then((response) => {
                console.log(response);
                let data = response.data;
                this.packages = data.results;
                this.totalResults = data.total;
                this.nextPageLink = data.next;
                this.previousPageLink=response.config.url;
            }).catch((err) => {
                console.log("no onternet");
                alert("No data found");
            });
        },
        methods: {
            search() {
                axios.get('https://packagist.org/search.json?q=' + this.packageSearch).then((response) => {
                    let data = response.data;
                    this.packages = data.results;
                    this.totalResults = data.total;
                    this.nextPageLink = data.next;
                    this.previousPageLink=response.config.url;
                }).catch((err) => {
                    console.log(err);
                });
            },
            navigatePage(pageUrl) {
                this.showLoading=true;
                axios.get(pageUrl).then((response) => {
                    console.log(response);
                    let data = response.data;
                    this.packages = data.results;
                    this.totalResults = data.total;
                    this.nextPageLink = data.next;
                    this.previousPageLink=response.config.url;
                    this.showLoading=false;
                }).catch((err)=>{
                    console.log(err);
                    alert("No data found");
                    this.showLoading=false;
                });
            },
            openPackageInstallationModal(packageName) {
                axios.get('https://packagist.org/packages/' + packageName + '.json').then((response) => {
                    this.packageInfo = response.data.package;
                    this.downloads = this.packageInfo.downloads;
                }).catch((err) => {
                    console.log(err);
                });
            },
            installPackageConfirmation(packageName) {
                let pack=packageName.replace("/", "^");
                this.eventSource = new EventSource('install-package/'+pack);

                this.installButtonText="Installing.....";
                this.eventSource.addEventListener('process_running', e => {
                    document.getElementById("result").innerHTML += e.data + "<br>";
                    console.log(e.data);
                }, false);

                this.eventSource.addEventListener('process_ended', (e) => {
                    this.installButtonText="Install Package";
                    this.eventSource.close();
                });
            }
        }
    }
</script>
