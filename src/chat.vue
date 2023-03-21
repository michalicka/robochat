<template>
	<div class="z-50 fixed bottom-0 left-0">
      <div v-show="!showChatWindow" class="fixed left-4 bottom-4 group">
        <img class="object-cover w-10 h-10 rounded-full cursor-pointer shadow" src="https://cdn.pixabay.com/photo/2018/01/15/07/51/woman-3083383__340.jpg" alt="username" @click="toggleChatWindow" />
        <span class="fixed left-16 bottom-8 text-sm text-blue-500 px-2 py-1 rounded-t-md rounded-r-md border border-blue-500 group-hover:bg-blue-500 group-hover:text-white cursor-pointer" @click="toggleChatWindow">How can I help you?</span>
      </div>
      <div v-show="showChatWindow" class="sm:w-80 w-full border rounded">
        <div>
          <div class="w-full">
            <div class="flex items-start justify-between border-b border-gray-300">
              <div class="relative flex items-center p-3">
                <img class="object-cover w-10 h-10 rounded-full"
                  src="https://cdn.pixabay.com/photo/2018/01/15/07/51/woman-3083383__340.jpg" alt="username" />
                <span class="block ml-2 font-bold text-gray-600">Emma</span>
                <span class="absolute w-3 h-3 bg-green-600 rounded-full left-10 top-3">
                </span>
              </div>
              <div class="p-3 w-8 h-4 cursor-pointer" @click="toggleChatWindow">
                <svg class="text-gray-600" viewPort="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
                  <line x1="1" y1="11" 
                        x2="11" y2="1" 
                        stroke="currentColor" 
                        stroke-width="2"/>
                  <line x1="1" y1="1" 
                        x2="11" y2="11" 
                        stroke="currentColor" 
                        stroke-width="2"/>
                </svg>
              </div>
            </div>
            <div class="relative w-full p-6 overflow-y-auto h-80">
              <ul class="space-y-2">
                <li class="flex justify-start">
                  <div class="relative max-w-xl px-4 py-2 text-gray-700 rounded shadow">
                    <span class="block">Hi</span>
                  </div>
                </li>
                <li class="flex justify-end">
                  <div class="relative max-w-xl px-4 py-2 text-gray-700 bg-gray-100 rounded shadow">
                    <span class="block">Hiiii</span>
                  </div>
                </li>
                <li class="flex justify-end">
                  <div class="relative max-w-xl px-4 py-2 text-gray-700 bg-gray-100 rounded shadow">
                    <span class="block">how are you?</span>
                  </div>
                </li>
                <li class="flex justify-start">
                  <div class="relative max-w-xl px-4 py-2 text-gray-700 rounded shadow">
                    <span class="block">Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                    </span>
                  </div>
                </li>
              </ul>
            </div>

            <div class="flex items-center justify-between w-full p-3 border-t border-gray-300">
              <button>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                </svg>
              </button>
              <input type="text" placeholder="Message"
                class="block w-full py-2 pl-4 mx-3 bg-gray-100 rounded-full outline-none focus:text-gray-700"
                name="message" required />
              <button type="submit">
                <svg class="w-5 h-5 text-gray-500 origin-center transform rotate-90" xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20" fill="currentColor">
                  <path
                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                </svg>
              </button>
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
      showChatWindow: false,
    }
  },
  methods: {
    toggleChatWindow() {
      this.showChatWindow = !this.showChatWindow;
      console.log(this.showChatWindow);
    },
  },
}
</script>
<style>
.speech-bubble {
  position: relative;
  background: #00aabb;
  border-radius: .4em;
}

.speech-bubble:after {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  width: 0;
  height: 0;
  border: 20px solid transparent;
  border-right-color: #00aabb;
  border-left: 0;
  border-bottom: 0;
  margin-top: -10px;
  margin-left: -20px;
}
</style>
