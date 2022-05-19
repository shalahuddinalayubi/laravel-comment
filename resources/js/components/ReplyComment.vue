<template>
    <div>
        <div class="text-xs mb-1 hover:cursor-pointer hover:underline" @click="show = !show">Reply</div>

        <span v-if="errorMessage" id="validation-comment-fails"></span>

        <form :action="action" method="POST" class="w-full py-3" v-show="show">
            <input type="hidden" name="_token" :value="csrf">

            <div class="flex py-2">
                <label for="content" class="mr-3">
                    <span class="flex items-center justify-center text-center inline-block w-10 h-10 rounded-full border-2">
                        {{ authUserName.substring(0, 1) }}
                    </span>
                </label>

                <div class="flex flex-col w-full">
                    <textarea
                        name="comment"
                        id="comment"
                        placeholder="Tambah komenter ..."
                        class="w-full shadow focus:ring-2 focus:ring-blue-500 appearance-none text-sm border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errorMessage }"
                    ></textarea>
                    <div class="text-xs text-red-500" v-if="errorMessage">
                        {{ errorMessage }}
                    </div>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white text-xs font-bold py-2 mt-2 px-4 rounded">Komentar</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'ReplyComment',

        data() {
            return {
                show: false
            }
        },

        methods: {
            showOnError () {
                if (this.errorMessage) {
                    this.show = true
                }
            }
        },

        props: [
            'route',
            'authUserName',
            'action',
            'csrf',
            'errorMessage'
        ],

        created() {
            this.showOnError()
        }
    }
</script>
