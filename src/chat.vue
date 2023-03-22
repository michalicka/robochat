<template>
	<div class="z-50 fixed bottom-0 right-0">
      <div v-show="!chatVisible" class="fixed right-4 bottom-4 group" @click="showChatWindow">
        <img class="object-cover w-10 h-10 rounded-full cursor-pointer shadow" :src="settings.assistant.image" alt="username" />
        <span class="absolute w-3 h-3 bg-green-600 rounded-full left-7 top-0"></span>
        <span class="fixed right-16 bottom-8 text-sm text-blue-500 px-2 py-1 rounded-t-md rounded-l-md border border-blue-500 group-hover:bg-blue-500 group-hover:text-white cursor-pointer">{{ settings.assistant.messages.intro }}</span>
      </div>
      <div v-show="chatVisible" class="sm:w-80 w-full border rounded">
        <div>
          <div class="w-full">
            <div class="flex items-start justify-between border-b border-gray-300">
              <div class="relative flex items-center p-3">
                <img class="object-cover w-10 h-10 rounded-full"
                  :src="settings.assistant.image" alt="username" />
                <span class="block ml-2 font-bold text-gray-600">{{ settings.assistant.name }}</span>
                <span class="absolute w-3 h-3 bg-green-600 rounded-full left-10 top-3"></span>
              </div>
              <div class="p-3 w-8 h-4 cursor-pointer" @click="hideChatWindow">
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
            <div ref="chatContent" class="relative w-full p-6 overflow-y-auto h-80">
              <ul class="space-y-2">
                <template v-for="message in messages">
                  <li v-if="message.role === 'assistant'" class="flex justify-start">
                    <div class="relative max-w-xl px-4 py-2 text-gray-700 rounded-md shadow text-xs">
                      <span class="block">{{ message.content }}</span>
                    </div>
                  </li>
                  <li v-if="message.role === 'user'" class="flex justify-end">
                    <div class="relative max-w-xl px-4 py-2 text-gray-700 bg-gray-100 rounded-md shadow text-xs">
                      <span class="block">{{ message.content }}</span>
                    </div>
                  </li>
                </template>
                <li v-if="loading && content" class="flex justify-start">
                  <div class="relative max-w-xl px-4 py-2 text-gray-700 rounded-md shadow text-xs">
                    <span class="block">{{ content }}</span>
                  </div>
                </li>
                <li v-if="finished" class="flex justify-start">
                  <div class="relative max-w-xl px-4 py-2 text-gray-700 rounded-md shadow text-xs">
                    <span class="block" v-html="finished"></span>
                  </div>
                </li>
              </ul>
            </div>

            <div v-if="!finished" class="flex items-center justify-between w-full p-3 border-t border-gray-300">
              <button>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                </svg>
              </button>
              <input ref="chatPrompt" type="text" placeholder="Message" :disabled="loading"
                class="block w-full py-2 px-4 mx-3 bg-gray-100 rounded-full outline-none focus:text-gray-700 text-sm"
                v-model="input" required @keyup.enter.prevent="addMessage('user', input)" />
              <button type="submit" @click.prevent="addMessage('user', input)">
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
let defaultMessages = [
  { role: "system", content: "Jsi pomocník (ženského pohlaví), který vytváří co nejlepší koučovací otázku. Neposkytuješ rady. Vždy odpovídáš koučovací otázkou." },
  { role: "assistant", content: "Co aktuálně řešíte? S čím potřebujete pomoci?" },
];
import parseJsonStream from './stream.js';
export default {
  data() {
    return {
      loading: false,
      chatVisible: false,
      finished: false,
      settings: {
        limit: 5,
        client: {
          api: {
            url: 'https://openai-client:8443/',
            key: '',
            org: '',
          }
        },
        assistant: {
          name: 'Robo',
          image: 'https://cdn.pixabay.com/photo/2023/03/05/21/11/ai-generated-7832244_640.jpg',
          messages: {
            intro: "Začněte online koučink ZDE",
            finished: 'To je pro dnes vše. Za domácí úkol mi pošlete odpovědi na tyto otázky na email: <a class="text-blue-500 underline" href="mailto:example@example.org">example@example.org</a>. Pokračovat budeme na koučovacím sezení. Pokud ještě nemáte termín, objednejte se emailem nebo na tel: <a class="text-blue-500 underline" href="tel:123456789">123456789</a>',
          }
        },
      },
      messages: [...defaultMessages],
      content: '',
      input: '',
    }
  },
  computed: {
    assistantCount() {
      return this.messages.filter(obj => obj.role === 'assistant').length;
    }
  },
  methods: {
    parseJsonStream,
    showChatWindow() {
      this.chatVisible = true;
      this.finished = false;
      this.focusPrompt();
    },
    focusPrompt() {
      this.$nextTick(() => {
         if (!this.finished) this.$refs.chatPrompt.focus();
      });
    },
    hideChatWindow() {
      this.chatVisible = false;
      this.messages = [...defaultMessages];
    },
    addMessage(role, content) {
      if (content) {
        this.messages.push({ role, content });
        this.input = '';
        this.focusPrompt();
        if (!this.loading && role === 'user') this.query();
        this.scrollDown();
      }
    },
    query() {
      this.loading = true;
      fetch(this.settings.client.api.url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + this.settings.client.api.key,
          'OpenAI-Organization': this.settings.client.api.org
        },
        body: JSON.stringify({
          model: "gpt-3.5-turbo",
          temperature: 0.8,
          top_p: 1,
          n: 1,
          stream: true,
          max_tokens: 4096 - JSON.stringify(this.messages).length,
          messages: this.messages
        })
      })
        .then(async (response) => {
            this.loading = true;
            for await (const chunk of this.parseJsonStream(response.body)) {
                this.model = chunk.model || '';
                if (!Array.isArray(chunk) && !chunk.choices[0].finish_reason) {
                    this.content = `${this.content}${chunk.choices[0].delta.content || chunk.choices[0].text || ''}`.trimStart();
                    this.scrollDown();
                } else {
                    this.loading = false;
                    this.addMessage('assistant', this.content);

                    if (this.assistantCount > 3 && !this.finished) {
                      this.finished = this.settings.assistant.messages.finished;
                      this.scrollDown();
                    }

                    this.content = '';
                }
            }
        })
        .catch(error => console.error(error));
    },
    scrollDown() {
      this.$nextTick(() => {
        this.$refs.chatContent.scrollTop = this.$refs.chatContent.scrollHeight;
      });
    }
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
