<template>
	<div v-if="settings.display.enabled" class="z-50 fixed bg-white" :class="{
      'bottom-0 left-0': settings.display.align === 'left',
      'bottom-0 right-0': settings.display.align === 'right',
    }">
      <div v-show="!chatVisible" class="fixed group" :class="{
          'left-4 bottom-4': settings.display.align === 'left',
          'right-4 bottom-4': settings.display.align === 'right',
        }" @click="showChatWindow">
        <img class="object-cover w-10 h-10 rounded-full cursor-pointer shadow" :src="settings.assistant.image" alt="username" />
        <span class="absolute w-3 h-3 bg-green-600 rounded-full left-7 top-0"></span>
        <span class="fixed text-sm text-blue-500 px-2 py-1 rounded-t-md border border-solid border-blue-500 group-hover:bg-blue-500 group-hover:text-white bg-white cursor-pointer" :class="{
            'left-16 bottom-8 rounded-r-md': settings.display.align === 'left',
            'right-16 bottom-8 rounded-l-md': settings.display.align === 'right',
          }">{{ settings.assistant.messages.intro }}</span>
      </div>
      <div v-show="chatVisible" class="sm:w-80 w-full border border-solid border-gray-300 rounded">
        <div>
          <div class="w-full">
            <div class="flex items-start justify-between border-b border-solid border-gray-300">
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

            <div v-if="!finished" class="flex items-center justify-between w-full p-3 border-t border-solid border-gray-300">
              <button v-if="false">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                </svg>
              </button>
              <input ref="chatPrompt" type="text" :disabled="loading"
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
import parseJsonStream from './stream.js';
export default {
  data() {
    return {
      loading: false,
      chatVisible: false,
      finished: false,
      settings: {
        display: {
          enabled: false,
        },
      },
      messages: [],
      content: '',
      input: '',
    }
  },
  computed: {
    assistantCount() {
      return this.messages.filter(obj => obj.role === 'assistant').length;
    },
    defaultMessages() {
      return [
        { role: "system", content: this.settings.assistant.messages.system },
        { role: "assistant", content: this.settings.assistant.messages.first },
      ];
    }
  },
  methods: {
    parseJsonStream,
    showChatWindow() {
      this.messages = [...this.defaultMessages];
      this.loading = false;
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
                    if (this.settings.assistant.limit && (this.assistantCount > this.settings.assistant.limit) && !this.finished) {
                      this.finished = this.settings.assistant.messages.finished.replace(
                        '{body}', 
                        encodeURIComponent(this.messages
                          .filter(obj => obj.role !== 'system')
                          .map(message => message.content)
                          .join('\r\n\r\n')
                        )
                      );
                      this.scrollDown();
                    }

                    this.content = '';
                }
            }
        })
        .catch(error => {
          console.error(error);
          this.addMessage('assistant', error.message);
          this.loading = false;
        });
    },
    scrollDown() {
      this.$nextTick(() => {
        this.$refs.chatContent.scrollTop = this.$refs.chatContent.scrollHeight;
      });
    },
    loadConfig() {
      const match = document.documentElement.outerHTML.match(/src="(.*?)robochat.min.js(.*?)"/i);
      const path = match ? match[1] : '';
      console.log(path);
      fetch(path + 'api.php?action=rbch_config')
        .then(response => response.json())
        .then(data => {
          this.settings = data;
          console.log(this.settings);
        })
        .catch(error => console.error('Error fetching config:', error));
    }
  },
  mounted() {
    this.loadConfig();
  }
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
