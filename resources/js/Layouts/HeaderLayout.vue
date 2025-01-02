<script setup lang="ts">
interface UrlPathType {
    [key: string]: string;
}
import NavLink from "@/Components/NavLink.vue";
const props = defineProps({
    path: {type:String, default: "/"}
})
let lastPath : string = "/"
let paths = props.path.split("/")
let UrlPath : UrlPathType = {
    "/": lastPath
}
for (const path  of paths) {
    if (path != "") {
        lastPath+=path
        UrlPath[lastPath] = path
    }
}
</script>

<template>
    <header class="flex flex-col sticky bg-[#31363b] text-white">
        <section class="flex w-full h-12">
            <div class="flex w-full items-center gap-3">
                <img class="aspect-square h-full" src="/storage/64/folder-blue.svg" alt="">
                <p>
                    {{$page.props.name}}
                </p>
            </div>
            <nav class="w-full">
            </nav>
        </section>
        <section class="h-10 flex gap-1 items-center pb-2 pt-2 ml-3 text-white">
            <span v-for="(name,path) in UrlPath" class="flex items-center gap-1" :href="path">
                <nav-link :href="route('files',{path: path})" class="p-3 pt-1 pb-1 transition hover:bg-emerald-400/10" >
                    {{name}}
                </nav-link>
                <span v-if="Object.keys(UrlPath).indexOf(path.toString()) != Object.keys(UrlPath).length-1">
                    >
                </span>
            </span>
        </section>
    </header>
</template>

<style scoped>

</style>
