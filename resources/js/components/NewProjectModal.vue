<template>
    <modal name="new-project-modal" classes="p-10 bg-card rounded-lg" height="auto">
        <h1 class="font-normal mb-10 text-center text-2xl">Let's start something new</h1>

        <form @submit.prevent="submit">
            <div class="flex">
                <div class="flex-1 mr-4">
                    <div class="mb-4">
                        <label for="title" class="text-sm block mb-2">Project Title</label>
                        <input 
                            type="text" 
                            id="title" 
                            class="border p-2 text-xs block w-full rounded"
                            :class="form.errors.title ? 'border-error' : 'border-muted-light'"
                            v-model="form.title">
                        <span 
                            class="text-xs italic text-error" 
                            v-if="form.errors.title"
                            v-text="form.errors.title[0]"></span>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="text-sm block mb-2">Project Description</label>
                        <textarea 
                            id="description" 
                            class="border p-2 text-xs block w-full rounded" 
                            rows="7" 
                            :class="form.errors.description ? 'border-error' : 'border-muted-light'"
                            v-model="form.description"></textarea>
                        <span 
                            class="text-xs italic text-error" 
                            v-if="form.errors.description"
                            v-text="form.errors.description[0]"></span>
                    </div>
                </div>
                <div class="flex-1 ml-4">
                    <div class="mb-4">
                        <label class="text-sm block mb-2">Let's add some tasks</label>
                        <input 
                            type="text" 
                            class="border border-muted-light p-2 mb-2 text-xs block w-full rounded" 
                            placeholder="Task 1" 
                            v-for="task in form.tasks"
                            v-model="task.body">
                    </div>

                    <button type="button" class="inline-flex items-center text-xs" @click="addTask">
                        <!-- CC-BY 4.0 (modified) https://fontawesome.com/license -->
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            width="18"
                            height="18"
                            viewBox="0 0 512 512" 
                            class="mr-2">
                            <path 
                                fill="#000"
                                opacity="0.5" 
                                d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm144 276c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92h-92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z"></path>
                        </svg>
                        <span>Add new task field</span>
                    </button>
                    
                </div>
            </div>

            <footer class="flex justify-end">
                <button type="button" class="button mr-4 is-outlined" @click="$modal.hide('new-project-modal')">Cancel</button>
                <button class="button">Create Project</button>
            </footer>
        </form>
    </modal>
</template>

<script>
    import BirdboardForm from './BirdboardForm';

    export default {
        data() {
            return {
                form: new BirdboardForm({
                    title: '',
                    description: '',
                    tasks: [
                        { body: '' },
                    ] 
                })
            }
        },
        methods: {
            addTask() {
                this.form.tasks.push({ body: '' });
            },

            async submit() {
                if (! this.form.tasks[0].body) {
                    delete this.form.originalData.tasks;
                }

                this.form.submit('/projects')
                    .then(response => location = response.data.redirectTo);
            }
        }
    }
</script>