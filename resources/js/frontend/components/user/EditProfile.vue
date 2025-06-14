<script setup>
    import { defineAsyncComponent, onMounted, reactive, ref } from 'vue';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_my_target: String,
        route_logout: String,
        route_update_user: String,
    });

    const Layout = defineAsyncComponent(() => import('../includes/Layout.vue'));

    const personal_data = reactive({
        user_id: props.user.id,
        full_name: '',
        phone: '',
        address: '',
        is_male: 1,
        current_password: '',
        new_password: '',
        confirm_password: '',
    });

    const isLoading = ref(false);

    const updateProfile = () => {

        isLoading.value = true;

        // validate
        if (personal_data.current_password && !personal_data.new_password) {
            alert('Please enter new password');
            isLoading.value = false;
            return;
        }

        if (personal_data.current_password && (personal_data.new_password && !personal_data.confirm_password)) {
            alert('Please enter confirm password');
            isLoading.value = false;
            return;
        }

        if (personal_data.current_password && (personal_data.new_password !== personal_data.confirm_password)) {
            alert('Password does not match');
            isLoading.value = false;
            return;
        }

        let form = new FormData();
        form.append('user_id', personal_data.user_id);
        form.append('full_name', personal_data.full_name);
        form.append('phone', personal_data.phone);
        form.append('address', personal_data.address);
        form.append('is_male', personal_data.is_male);
        if (personal_data.current_password) {
            form.append('current_password', personal_data.current_password);
            form.append('new_password', personal_data.new_password);
            form.append('confirm_password', personal_data.confirm_password);
        }

        try {
            // post data method patch to route_update_user
            axios.post(props.route_update_user, form)
                .then(response => {
                    console.log(response.data);

                    if (response.data.success) {
                        alert('Profile updated');

                        // reload page
                        location.reload();
                    }

                    if (response.data.error) {
                        alert(response.data.error);
                    }
                })
                .catch(error => {
                    console.log(error);

                    // if error validation code 422
                    // loop the erros
                    if (error.response.status === 422) {
                        let errors = error.response.data.errors;
                        let message = '';
                        for (let key in errors) {
                            message += errors[key][0] + '\n';
                        }
                        alert(message);
                    }
                })
                .finally(() => {
                    isLoading.value = false;
                });
        }
        catch (error) {
            console.log(error);

            isLoading.value = false;
        }
    }

    onMounted(() => {
        personal_data.full_name = props.user.name;
        personal_data.phone = props.user.personal_data?.phone;
        personal_data.address = props.user.personal_data?.address;
        personal_data.is_male = props.user.personal_data?.is_male ?? 1;
    });


</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_my_target="route_my_target" :route_logout="route_logout" :user="user" :csrf="csrf">
        <div class="flex justify-between items-center bg-primary-50 px-14 py-7">
            <div class="font-bold text-white text-2xl uppercase tracking-widest">PERSONAL DATA</div>
        </div>

        <div class="py-10 container">
            <div class="items-center grid grid-cols-6 mb-4">
                <label for="full_name" class="block col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest">Full name</label>
                <input type="text" id="full_name" v-model.lazy="personal_data.full_name" disabled class="block col-span-5 bg-transparent p-2.5 border border-primary-50 rounded-full w-full text-primary-50"/>
            </div>
            <div class="items-center grid grid-cols-6 mb-4">
                <label for="phone" class="block col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest">Phone No</label>
                <input type="text" id="phone"
                    v-model.lazy="personal_data.phone"
                    class="block col-span-5 bg-transparent p-2.5 border border-primary-50 rounded-full w-full text-primary-50"/>
            </div>
            <div class="items-center grid grid-cols-6 mb-4">
                <label for="email" class="block self-start col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest">Address</label>
                <textarea rows="4" name="" id=""
                    v-model.lazy="personal_data.address"
                    class="block col-span-5 bg-transparent p-2.5 border border-primary-50 rounded-3xl w-full text-primary-50"></textarea>
                <!-- <input type="email" id="email" class="block col-span-5 bg-transparent p-2.5 border border-primary-50 rounded-full w-full text-primary-50"/> -->
            </div>
            <div class="items-center grid grid-cols-6">
                <label class="block col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest">gender</label>
                <div class="flex gap-4 col-span-5">
                    <div class="flex items-center">
                        <input id="default-radio-1" v-model="personal_data.is_male" type="radio" value="1" name="default-radio" class="bg-secondary border-gray-300 focus:ring-secondary w-4 h-4 text-secondary-50">
                        <label for="default-radio-1" class="ms-1 mt-1 text-primary-50 uppercase">Male</label>
                    </div>
                    <div class="flex items-center">
                        <input id="default-radio-2" v-model="personal_data.is_male" type="radio" value="0" name="default-radio" class="bg-secondary border-gray-300 focus:ring-secondary w-4 h-4 text-secondary-50">
                        <label for="default-radio-2" class="ms-1 mt-1 text-primary-50 uppercase">Female</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change password -->
        <div class="flex justify-between items-center bg-primary-50 px-14 py-7">
            <div class="font-bold text-white text-2xl uppercase tracking-widest">Change password
                <span class="text-base">
                    (Leave blank if you don't want to change password)
                </span>
            </div>
        </div>

        <div class="py-10 container">
            <div class="items-center gap-4 grid grid-cols-8 mb-4">
                <label for="recent-pass" class="block col-span-2 mb-2 font-medium text-primary-50 uppercase tracking-widest">recent password</label>
                <input type="password" v-model="personal_data.current_password" id="recent-pass" class="block col-span-6 bg-transparent p-2.5 border border-primary-50 rounded-full w-full text-primary-50"/>
            </div>
            <div class="items-center gap-4 grid grid-cols-8 mb-4">
                <label for="new-pass" class="block col-span-2 mb-2 font-medium text-primary-50 uppercase tracking-widest">new password</label>
                <input type="password" v-model="personal_data.new_password" id="new-pass" class="block col-span-6 bg-transparent p-2.5 border border-primary-50 rounded-full w-full text-primary-50"/>
            </div>
            <div class="items-center gap-4 grid grid-cols-8 mb-4">
                <label for="confirm-pass" class="block col-span-2 mb-2 font-medium text-primary-50 uppercase tracking-widest">CONFIRM password</label>
                <input type="password" v-model="personal_data.confirm_password" id="confirm-pass" class="block col-span-6 bg-transparent p-2.5 border border-primary-50 rounded-full w-full text-primary-50"/>
            </div>
        </div>

        <div class="float-right">
                <button @click="updateProfile" :disabled="isLoading"
                    class="flex items-center gap-2 bg-secondary-50 px-14 py-6 text-white tracking-widest"
                    :class="{'opacity-50': isLoading}"
                    >
                    <span class="uppercase">update</span>
                    <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                </button>
            </div>
    </Layout>
</template>
