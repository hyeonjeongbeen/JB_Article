<template>
    <div class="container-fluid">
        <!-- <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <router-link to="/">Home</router-link>
            </li>
            <li class="breadcrumb-item active">Articles</li>
        </ol> -->

        <div class="card mb-3">
            <div class="card-header d-flex">
                <span>
                    <i class="fas fa-chart-area"></i>
                    게시글
                </span>
                <button class="btn btn-primary btn-sm ml-auto" v-on:click="showNewArticleModal"><span class="fa fa-plus"></span>작성</button>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>username</td>
                            <td>Title</td>
                            <td>Image</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(article, index) in articleies" :key="index">
                            <td>{{index+1}}</td>
                            <td>{{article.username}}</td>
                            <td>{{article.title}}</td>
                            <td>
                                <img :src="`${$store.state.serverPath}/storage/${article.image}`"
                                :alt="article.title" class="table-image" />
                            </td>
                            <td v-if="app.user.username == article.username">
                                <button class="btn btn-primary btn-sm" v-on:click="editArticle(article)"><span class="fa fa-edit">수정</span></button>
                                <button class="btn btn-danger btn-sm" v-on:click="deleteArticle(article)"><span class="fa fa-trash">삭제</span></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center" v-show="moreExists">
                    <button v-show="nextPage != 1" type="button" class="btn btn-primary btn-sm" v-on:click="loadBack"><span class="fa fa-arrow-down"></span>이전 글 보기</button>
                    page : {{nextPage}}
                    <button v-show="nextPage != lastPage" type="button" class="btn btn-primary btn-sm" v-on:click="loadMore"><span class="fa fa-arrow-down"></span>다음 글 보기</button>
                </div>
            </div>
        </div>

        <b-modal ref="newArticleModal" hide-footer title="Add New Article">
            <div class="d-block">
                <form v-on:submit.prevent="createArticle">
                    <div class="mb-3">
                        <label for="title">Title</label>
                        <input type="text" v-model="articleData.title" class="form-control" id="title"
                        placeholder="title를 입력해주세요!">
                        <div class="invalid-feedback" v-if="errors.title">{{errors.title[0]}}</div>                     
                    </div>
                    <div class="mb-3">
                        <label for="image">Choose an image</label>
                        <div v-if="articleData.image.name">
                            <img src="" ref="newArticleImageDispaly" class="w-200px" />
                        </div>
                        <input type="file" v-on:change="attachImage" ref="newArticleImage" class="form-control" id="image" />                       
                        <div class="invalid-feedback" v-if="errors.image">{{errors.image[0]}}</div>
                    </div>

                    <hr>
                    <div class="text-right">
                        <button type="button" class="btn btn-default" v-on:click="hideNewArticleModal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><span class="fa fa-check"></span>Save</button>
                    </div>
                </form>
            </div>
        </b-modal>

        <b-modal ref="editArticleModal" hide-footer title="Update Article">
            <div class="d-block">
                <form v-on:submit.prevent="updateArticle">
                    <div class="mb-3">
                        <label for="title">Title</label>
                        <input type="text" v-model="editArticleData.title" class="form-control" id="title"
                        placeholder="Enter article name">
                        <div class="invalid-feedback" v-if="errors.title">{{errors.title[0]}}</div>                     
                    </div>
                    <div class="mb-3">
                        <label for="image">Choose an image</label>
                        <div>
                            <img :src="`${$store.state.serverPath}/storage/${editArticleData.image}`" ref="editArticleImageDispaly" class="w-200px" />
                        </div>
                        <input type="file" v-on:change="editAttachImage" ref="editArticleImage" class="form-control" id="image" />                       
                        <div class="invalid-feedback" v-if="errors.image">{{errors.image[0]}}</div>
                    </div>

                    <hr>
                    <div class="text-right">
                        <button type="button" class="btn btn-default" v-on:click="hideEditArticleModal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><span class="fa fa-check"></span>update</button>
                    </div>
                </form>
            </div>
        </b-modal>

    </div>
</template>

<script>
import * as articleService from '../services/article_service';

export default {
    name:'Article',
    props: ['app'],
    data() {
        return {
            articleies: [],
            articleData: {
                username: '',
                title: '',
                image: '',
            },
            moreExists: true,
            backExists: true,
            nextPage: 1,
            lastPage: 0,
            editArticleData:{},
            errors: {},
        }
    },
    mounted() {
        this.loadArticleies();
    },
    methods: {
        loadArticleies: async function() {
            try {
                console.log("로드 호출");
                const response = await articleService.loadArticleies();
                console.log(response, '이거 response');
                this.articleies = response.data.data;
                // if (response.data.current_page < response.data.last_page) {
                    //     this.moreExists = true;
                //     this.nextPage = response.data.current_page + 1;
                // } else {
                    //     this.moreExists = false;
                // }
                // this.articleies = response.data.articleies.data;
                this.lastPage = response.data.last_page
                console.log(this.lastPage, '이거 lastPage');
                console.log(response.data.last_page, 'res');
                console.log(this.articleies, '이거 articleies');
            } catch (error) {
                console.log(error,'에러');
                this.flashMessage.error({
                        message: '오류가 발생했습니다. 새로 고치십시오!',
                        time: 5000
                });
            }
        },
        attachImage(){
            this.articleData.image = this.$refs.newArticleImage.files[0];
            let reader = new FileReader();
            reader.addEventListener('load',function() {
                this.$refs.newArticleImageDispaly.src = reader.result;
            }.bind(this), false);

            reader.readAsDataURL(this.articleData.image);
        },
        hideNewArticleModal() {
            this.$refs.newArticleModal.hide();
        },
        showNewArticleModal() {
            this.$refs.newArticleModal.show();
        },
        createArticle: async function() {
            let formData = new FormData();
            formData.append('title', this.articleData.title);
            formData.append('image',this.articleData.image);
            formData.append('username', this.app.user.username);
            try {
                const response = await articleService.createArticle(formData);
                this.articleies.unshift(response.data);
                this.hideNewArticleModal();
                this.flashMessage.success({
                    message: '게시글 저장 성공했습니다!',
                    time: 5000
                });
                this.articleData = {
                    title: '',
                    image: '',
                    username: ''
                };
            } catch (error) {
                switch (error.response.status) {
                    case 422:
                        this.errors = error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                        message: '오류가 발생했습니다. 다시 시도하십시오!',
                        time: 5000
                    });
                        break;
                }
            }
        },
        deleteArticle: async function(article) {
            console.log(article.id,'id 번호');
            if(!window.confirm(`정말로 "${article.title}"를 삭제 하시겠습니까?`)) {
                return;
            }

            try {
                await articleService.deleteArticle(article.id);
                console.log('딜리트 성공');

                this.loadArticleies();
                // this.articleies = this.articleies.filter(obj => {
                //     return obj.id != this.article.id;
                // });
                 this.flashMessage.success({
                    message: '성공적으로 삭제되었습니다!',
                    time: 5000
                });
            } catch (error) {
                this.flashMessage.error({
                    message: error.response.data.message,
                    time: 5000
                });
            }
        },
        hideEditArticleModal() {
            this.$refs.editArticleModal.hide();
        },
        showEditArticleModal() {
            this.$refs.editArticleModal.show();
        },
        editArticle(article) {
            this.editArticleData = {...article};
            this.showEditArticleModal();
        },
        editAttachImage(){
            this.editArticleData.image = this.$refs.editArticleImage.files[0];
            let reader = new FileReader();
            reader.addEventListener('load',function() {
                this.$refs.editArticleImageDispaly.src = reader.result;
            }.bind(this), false);

            reader.readAsDataURL(this.editArticleData.image);
        },
        updateArticle: async function() {
            try {
                const formData = new FormData();
                formData.append('title',this.editArticleData.title);
                formData.append('image',this.editArticleData.image);
                formData.append('_method','put');

                const response = await articleService.updateArticle(this.editArticleData.id, formData);
                this.articleies.map(article => {
                    if(article.id == response.data.id) {
                        for (let key in response.data) {
                            article[key] = response.data[key];
                        }
                    }
                });
                this.loadArticleies();
                this.hideEditArticleModal();
                this.flashMessage.success({
                    message: '성공적으로 업데이트가 되었습니다! ',
                    time: 5000
                });
            } catch (error) {
                this.errors = error.response.data.errors;
                this.flashMessage.error({
                    message: error.response.data.message,
                    time: 5000
                });
                console.log('에러발생');
            
            }
        },
        loadMore: async function() {
            try {
                this.nextPage += 1;
                const response = await articleService.loadMore(this.nextPage);
                 if (response.data.current_page == response.data.last_page) {
                    // this.moreExists = false;
                    this.nextPage = response.data.last_page;
                    console.log("엘스모어");
                }
                else{
                    // this.moreExists = true;
                }

                this.articleies = [];
                response.data.data.forEach(data => {
                    this.articleies.push(data);
                });
            } catch (error) {
                 this.flashMessage.error({
                    message: '더 많은 범주를 로드하는 동안 일부 오류가 발생했습니다.',
                    time: 5000
                });
            }
        }
        ,
        loadBack: async function() {
            console.log("백실행");
            try {
                this.nextPage -= 1;
                const response = await articleService.loadMore(this.nextPage);
                if (response.data.current_page == response.data.first_page) {
                    // this.backExists = false;
                    this.nextPage = response.data.first_page;
                    console.log("백엘스");
                }
                else{
                    // this.backExists = true;
                }

                this.articleies = [];
                response.data.data.forEach(data => {
                    this.articleies.push(data);
                });
            } catch (error) {
                 this.flashMessage.error({
                    message: '더 많은 범주를 로드하는 동안 일부 오류가 발생했습니다.',
                    time: 5000
                });
            }
        },
    }
};
</script>

<style>

</style>