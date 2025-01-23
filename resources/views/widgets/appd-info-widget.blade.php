<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <a href="#" rel="noopener noreferrer" target="_blank">
                <img src="{{ url('img/logo.svg') }}" />
            </a>

            <div class="flex-1">
                <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ config('app.name') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    v2.0.0
                </p>
            </div>

            <div class="flex flex-col items-end gap-y-1">
                <x-filament::link color="gray" href="#"
                    icon-alias="panels::widgets.filament-info.open-documentation-button" rel="noopener noreferrer"
                    target="_blank">
                    <x-slot name="icon">
                        <svg height="20px" width="20px" version="1.1" id="Layer_1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 128.5 128.5" xml:space="preserve">
                            <g>
                                <g>
                                    <circle style="fill:#B1C95D;" cx="64.25" cy="64.25" r="64.25" />
                                </g>
                                <polygon style="fill:#DDA272;" points="105,99 30,99 30,38 51,38 51,43 105,43 	" />
                                <polygon style="fill:#FFFFFF;"
                                    points="94,92 39,92 39,20 75.979,20 85.776,29.931 94,38.436 	" />
                                <polygon style="fill:#D8D8D8;" points="76,38 93.642,38 76,19.713 	" />
                                <path style="fill:#D8D8D8;"
                                    d="M68,28H45c-1.105,0-2-0.896-2-2s0.895-2,2-2h23c1.104,0,2,0.896,2,2S69.104,28,68,28z" />
                                <path style="fill:#D8D8D8;"
                                    d="M55,37H44c-0.553,0-1-0.448-1-1s0.447-1,1-1h11c0.553,0,1,0.448,1,1S55.553,37,55,37z" />
                                <path style="fill:#D8D8D8;"
                                    d="M68,37h-8c-0.553,0-1-0.448-1-1s0.447-1,1-1h8c0.553,0,1,0.448,1,1S68.553,37,68,37z" />
                                <path style="fill:#D8D8D8;"
                                    d="M76,50H64c-0.553,0-1-0.448-1-1s0.447-1,1-1h12c0.553,0,1,0.448,1,1S76.553,50,76,50z" />
                                <path style="fill:#D8D8D8;"
                                    d="M88,50h-7c-0.553,0-1-0.448-1-1s0.447-1,1-1h7c0.553,0,1,0.448,1,1S88.553,50,88,50z" />
                                <path style="fill:#D8D8D8;"
                                    d="M73,43H57c-0.553,0-1-0.448-1-1s0.447-1,1-1h16c0.553,0,1,0.448,1,1S73.553,43,73,43z" />
                                <path style="fill:#D8D8D8;"
                                    d="M59,50H44c-0.553,0-1-0.448-1-1s0.447-1,1-1h15c0.553,0,1,0.448,1,1S59.553,50,59,50z" />
                                <path style="fill:#D8D8D8;"
                                    d="M53,43h-9c-0.553,0-1-0.448-1-1s0.447-1,1-1h9c0.553,0,1,0.448,1,1S53.553,43,53,43z" />
                                <path style="fill:#D8D8D8;"
                                    d="M88,43H78c-0.553,0-1-0.448-1-1s0.447-1,1-1h10c0.553,0,1,0.448,1,1S88.553,43,88,43z" />
                                <path style="fill:#D8D8D8;"
                                    d="M73,56H57c-0.553,0-1-0.448-1-1s0.447-1,1-1h16c0.553,0,1,0.448,1,1S73.553,56,73,56z" />
                                <path style="fill:#D8D8D8;"
                                    d="M53,56h-9c-0.553,0-1-0.448-1-1s0.447-1,1-1h9c0.553,0,1,0.448,1,1S53.553,56,53,56z" />
                                <path style="fill:#D8D8D8;"
                                    d="M88,56H78c-0.553,0-1-0.448-1-1s0.447-1,1-1h10c0.553,0,1,0.448,1,1S88.553,56,88,56z" />
                                <polygon style="fill:#EFB67F;"
                                    points="105,99 29.757,99 18.872,57.713 17,52 44.149,52 46.021,58 94.116,58 	" />
                                <polygon style="fill:#FFFFFF;" points="58.357,92 36.735,92 33.809,80 55.431,80 	" />
                                <polygon style="fill:#FFFFFF;" points="43.378,57 21.757,57 20.703,54 42.324,54 	" />
                                <path style="fill:#DDA272;"
                                    d="M90,82H67c-0.553,0-1-0.448-1-1s0.447-1,1-1h23c0.553,0,1,0.448,1,1S90.553,82,90,82z" />
                                <path style="fill:#DDA272;"
                                    d="M91,86H68c-0.553,0-1-0.448-1-1s0.447-1,1-1h23c0.553,0,1,0.448,1,1S91.553,86,91,86z" />
                                <path style="fill:#DDA272;"
                                    d="M92,91H70c-0.553,0-1-0.448-1-1s0.447-1,1-1h22c0.553,0,1,0.448,1,1S92.553,91,92,91z" />
                            </g>
                        </svg>
                    </x-slot>
                    Documentação
                </x-filament::link>

                <x-filament::link color="gray" href="#"
                    icon-alias="panels::widgets.filament-info.open-github-button" rel="noopener noreferrer"
                    target="_blank">
                    <x-slot name="icon">
                        <svg height="20px" width="20px" version="1.1" id="Layer_1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 508 508" xml:space="preserve">
                            <circle style="fill:#54C0EB;" cx="254" cy="254" r="254" />
                            <path style="fill:#FFFFFF;" d="M303.7,303.3c30.5-17.3,51-50.1,51-87.6c0-55.7-45.1-100.8-100.8-100.8S153.2,160,153.2,215.6
                       c0,37.6,20.6,70.3,51,87.6C141,319.3,89.7,365,66,424.8c46.5,51.1,113.5,83.2,188,83.2s141.5-32.1,188-83.2
                       C418.3,365,367,319.3,303.7,303.3z" />
                            <path style="fill:#324A5E;" d="M401.6,182.3h-15.8C370.9,123.4,317.5,79.6,254,79.6s-116.9,43.7-131.8,102.7h-15.8
                       c-5.4,0-9.8,4.4-9.8,9.8V240c0,5.4,4.4,9.8,9.8,9.8h20c6.1,0,10.8-5.5,9.7-11.4c-2-10.4-2.7-21.3-1.8-32.5
                       c4.8-59.3,53.6-106.9,113.1-110.1c69.2-3.8,126.8,51.5,126.8,119.9c0,7.8-0.8,15.3-2.2,22.7c-1.2,6,3.6,11.5,9.6,11.5h1.8
                       c-4.2,13-14.9,37.2-38.3,50.2c-19.6,10.9-44.3,11.9-73.4,2.8c-1.5-6.7-8.9-14.6-16.5-18.3c-9.8-4.9-15.9-0.8-19.4,6.2
                       s-3,14.3,6.7,19.2c8.6,4.3,21.6,5.2,27,0.5c13.9,4.3,26.9,6.5,39,6.5c15,0,28.5-3.3,40.4-10c27.5-15.3,38.8-43.7,42.8-57.2h9.9
                       c5.4,0,9.8-4.4,9.8-9.8v-47.9C411.4,186.7,407,182.3,401.6,182.3z" />
                        </svg>
                    </x-slot>

                    Suporte
                </x-filament::link>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
