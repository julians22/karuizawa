<template>
    <div class="relative min-h-svh mb-28">
        <!-- collar -->
        <div>
            <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                <div class="text-xl font-bold tracking-widest text-white uppercase">02. Collar</div>
            </div>

            <div class="grid grid-cols-6 gap-2 my-10 px-14 whitespace-nowrap">
                <div class="col-span-6 pt-2 xl:col-span-2">
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`low-regular`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`low-regular`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">2. LOW REGULAR</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`low-semi-wide`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`low-semi-wide`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">5. LOW SEMI WIDE</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`wide`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`wide`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">6. WIDE</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`round`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`round`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">8. ROUND</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`large-round`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`large-round`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">9. LARGE ROUND</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`low-bottom-down`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`low-bottom-down`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">12. LOW BOTTOM DOWN</div>
                        </label>
                    </div>
                    <div class="mt-6 text-xs text-pink-ka font-roboto">*No Additional Charge for above items</div>
                </div>
                <div class="col-span-6 xl:col-span-4">
                    <div class="grid grid-cols-1 gap-4 p-2 border 2xl:grid-cols-3 border-pink-ka">
                        <div class="col-1">
                            <div>
                                <input class="hidden" type="radio" name="collar" :id="`due-wide`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`due-wide`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">14. DUE WIDE </div>
                                </label>
                            </div>
                            <div>
                                <input class="hidden" type="radio" name="collar" :id="`due-button-down`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`due-button-down`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">15. DUE BUTTON DOWN</div>
                                </label>
                            </div>
                            <div>
                                <input class="hidden" type="radio" name="collar" :id="`tab-hook`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`tab-hook`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">16. TAB HOOK</div>
                                </label>
                            </div>
                        </div>
                        <div class="col-2">
                            <div>
                                <input class="hidden" type="radio" name="collar" :id="`pin-hole`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`pin-hole`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">17. PIN HOLE </div>
                                </label>
                            </div>
                            <div>
                                <input class="hidden" type="radio" name="collar" :id="`snap-down`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`snap-down`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">18. SNAP DOWN </div>
                                </label>
                            </div>
                        </div>
                        <div class="self-end col-3 justify-items-end">
                            <div class="">
                                <input class="hidden" type="radio" name="collar-50" :id="`50rb`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`50rb`">
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-pink-ka"> Rp 50.000 </div>
                                    <span class="checkbox-inner pink"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 p-2 border border-pink-ka">
                        <div class="col-span-3">
                            <div>
                                <input class="hidden" type="radio" name="collar" :id="`mitre-btn-down`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`mitre-btn-down`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">19. MITRE BUTTON DOWN </div>
                                </label>
                            </div>
                            <div>
                                <input class="hidden" type="radio" name="collar" :id="`joining-due-btn-down`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`joining-due-btn-down`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">20. JOINING DUE BUTTON DOWN</div>
                                </label>
                            </div>
                            <div>
                                <input class="hidden" type="radio" name="collar" :id="`pieces-collar-due-btn-down`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`pieces-collar-due-btn-down`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">21. 2 PIECES COLLAR DUE BUTTON DOWN</div>
                                </label>
                            </div>
                            <div>
                                <input class="hidden" type="radio" name="collar" :id="`wing-collar`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`wing-collar`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">22. WING COLAR</div>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center col-span-2 gap-4 mt-4 fabric-code">
                            <label for="fabric-code" class="tracking-widest uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                            <div class="flex font-roboto">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                            </div>
                        </div>
                        <div class="self-end col-3 justify-items-end">
                            <div class="">
                                <input class="hidden" type="radio" name="collar-100" :id="`100rb`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`100rb`">
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-pink-ka">Rp 100.000 </div>
                                    <span class="checkbox-inner pink"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cuffs -->
        <div>
            <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                <div class="text-xl font-bold tracking-widest text-white uppercase">03. cuffs</div>
            </div>

            <div class="grid grid-cols-6 gap-2 my-10 px-14 whitespace-nowrap">
                <div class="col-span-2 pt-2">
                    <div>
                        <input class="hidden" type="radio" name="cuffs" :id="`cuffs-large-round`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-large-round`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">3. LARGE ROUND</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cuffs" :id="`large-mitreed`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`large-mitreed`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">4. LARGE MITREED</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cuffs" :id="`square`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`square`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">5. SQUARE</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cuffs" :id="`mitred-twin-button`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`mitred-twin-button`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">6. MITRED TWIN BUTTON</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cuffs" :id="`round-twin-button`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`round-twin-button`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">7. ROUND TWIN BUTTON</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cuffs" :id="`short-round`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`short-round`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">8. SHORT ROUND </div>
                        </label>
                    </div>
                    <div class="mt-6 text-xs text-pink-ka font-roboto">*No Additional Charge for above items</div>
                </div>
                <div class="col-span-4">
                    <div>
                        <div class="grid grid-cols-1 gap-4 p-2 border 2xl:grid-cols-3 border-pink-ka">
                            <div class="">
                                <div>
                                    <input class="hidden" type="radio" name="cuffs" :id="`double-cuffs`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`double-cuffs`">
                                        <span class="checkbox-inner"></span>
                                        <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">10. DOUBLE CUFFS</div>
                                    </label>
                                </div>
                            </div>
                            <div></div>
                            <div class="self-end col-3 justify-items-end">
                                <div class="">
                                    <input class="hidden" type="radio" name="cuffs-70" :id="`cuffs-70rb`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-70rb`">
                                        <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-pink-ka">Rp 70.000 </div>
                                        <span class="checkbox-inner pink"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 p-2 border border-pink-ka">
                            <div class="col-span-3">
                                <div>
                                    <input class="hidden" type="radio" name="cuffs" :id="`joining-cuffs`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`joining-cuffs`">
                                        <span class="checkbox-inner"></span>
                                        <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">11. JOINING CUFFS</div>
                                    </label>
                                </div>
                            </div>
                            <div class="flex items-center col-span-2 gap-8 mt-4 fabric-code">
                                <label for="fabric-code" class="tracking-widest uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                                <div class="flex font-roboto">
                                    <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                                    <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                    <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                    <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                </div>
                            </div>
                            <div class="self-end col-3 justify-items-end">
                                <div class="">
                                    <input class="hidden" type="radio" name="cuffs-100" :id="`cuffs-100rb`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-100rb`">
                                        <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-pink-ka">Rp 100.000 </div>
                                        <span class="checkbox-inner pink"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-6">
                        <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                            <div class="text-xl font-bold tracking-widest text-white uppercase">04. FRONT BODY</div>
                        </div>
                        <div class="flex justify-between p-2 mt-4 border border-pink-ka">
                            <div>
                                <input class="hidden" type="radio" name="front-body" :id="`hidden-placket`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`hidden-placket`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">3. HIDDEN PLACKET</div>
                                </label>
                            </div>
                            <div class="">
                                <input class="hidden" type="radio" name="front-bdy-100" :id="`front-bdy-100`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`front-bdy-100`">
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-pink-ka">Rp 100.000 </div>
                                    <span class="checkbox-inner pink"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- button & body snap button -->
        <div class="flex *:w-full gap-4">
            <div class="">
                <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                    <div class="text-xl font-bold tracking-widest text-white uppercase">08. BUTTON</div>
                </div>
                <div class="flex justify-between p-2 mt-4 border border-pink-ka ml-14">
                    <div>
                        <input class="hidden" type="radio" name="button" :id="`shell-placket`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`shell-placket`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">SHELL BUTTON</div>
                        </label>
                    </div>
                    <div class="flex font-roboto">
                        <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                        <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                        <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                        <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                    </div>
                    <div class="">
                        <input class="hidden" type="radio" name="button-100" :id="`button-100`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`button-100`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-pink-ka">Rp 100.000</div>
                            <span class="checkbox-inner pink"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                    <div class="text-xl font-bold tracking-widest text-white uppercase">09. BODY SNAP BUTTON</div>
                </div>
                <div class="flex justify-between p-2 mt-4 border border-pink-ka mr-14">
                    <div>
                        <input class="hidden" type="radio" name="body-snap-btn" :id="`snap-btn`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`snap-btn`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">2.5 SNAP BUTTON</div>
                        </label>
                    </div>
                    <div class="">
                        <input class="hidden" type="radio" name="bdy-snap-100" :id="`bdy-snap-50`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`bdy-snap-50`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-pink-ka">Rp 50.000 </div>
                            <span class="checkbox-inner pink"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- cleric -->
        <div class="mt-4">
            <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                <div class="text-xl font-bold tracking-widest text-white uppercase">10. Cleric</div>
            </div>

            <div class="grid grid-cols-5 p-4 my-4 border mx-14 border-pink-ka">
                <div class="grid grid-cols-3 col-span-3 gap-2">
                    <div>
                        <input class="hidden" type="radio" name="cleric-collar-01" :id="`cleric-collar-01`">
                        <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-collar-01`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">collar</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cleric-collar-stand-01" :id="`cleric-collar-stand-01`">
                        <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-collar-stand-01`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">collar stand</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cleric-cuffs-01" :id="`cleric-cuffs-01`">
                        <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-cuffs-01`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">cuffs</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cleric-collar-02" :id="`cleric-collar-02`">
                        <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-collar-02`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">collar</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cleric-collar-stand-02" :id="`cleric-collar-stand-02`">
                        <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-collar-stand-02`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">collar stand</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cleric-cuffs-02" :id="`cleric-cuffs-02`">
                        <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-cuffs-02`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">cuffs</div>
                        </label>
                    </div>
                    <div class="flex col-span-3 gap-4">
                        <div>
                            <input class="hidden" type="radio" name="cleric-inner-collar-stand" :id="`cleric-inner-collar-stand`">
                            <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-inner-collar-stand`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">inner coller stand</div>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="radio" name="cleric-inner-cuffs" :id="`cleric-inner-cuffs`">
                            <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-inner-cuffs`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">inner cuffs</div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-span-2">
                    <div class="w-full h-8"></div>
                    <div>
                        <input class="hidden" type="radio" name="cleric-front-placket" :id="`cleric-front-placket`">
                        <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-front-placket`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">front placket</div>
                        </label>
                    </div>

                    <div class="flex justify-between">
                        <div class="flex-col items-center col-span-2 gap-4 mt-4 fabric-code">
                            <label for="fabric-code" class="tracking-widest uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                            <div class="flex font-roboto">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                            </div>
                        </div>
                        <div class="self-end">
                            <input class="hidden" type="radio" name="cleric-snap-100" :id="`cleric-snap-50`">
                            <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cleric-snap-50`">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-pink-ka">Rp 50.000 </div>
                                <span class="checkbox-inner pink"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 p-4 my-4 border mx-14 border-pink-ka">
                <div class="flex gap-20 ">
                    <div>
                        <input class="hidden" type="radio" name="cleric-inner-collar-stand-02" :id="`cleric-inner-collar-stand-02`">
                        <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-inner-collar-stand-02`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">inner collar stand</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cleric-inner-cuffs-02" :id="`cleric-inner-cuffs-02`">
                        <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-inner-cuffs-02`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">inner cuffs</div>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="cleric-lower-placket" :id="`cleric-lower-placket`">
                        <label class="flex items-center h-full gap-4 rounded cursor-pointer" :for="`cleric-lower-placket`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">lower placket</div>
                        </label>
                    </div>
                </div>
                <div class="self-end justify-items-end">
                    <div class="flex gap-10">
                        <div class="flex-col items-center col-span-2 gap-4 mt-4 fabric-code">
                            <label for="fabric-code" class="tracking-widest uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                            <div class="flex font-roboto">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                            </div>
                        </div>
                        <div class="self-end">
                            <input class="hidden" type="radio" name="cleric-snap-100" :id="`cleric-snap-100`">
                            <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cleric-snap-100`">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-pink-ka">Rp 100.000 </div>
                                <span class="checkbox-inner pink"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- button hole, button thread, stitch thread -->
         <!-- <div class="flex">
            <div>
                <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                    <div class="text-xl font-bold tracking-widest text-white uppercase">11. BUTTON Hole</div>
                </div>
                <div class="grid grid-cols-2 p-2 mt-4 border border-pink-ka mx-14">
                    <div>
                        <div>
                            <input class="hidden" type="radio" name="btn-hole" :id="`btn-hole-white`">
                            <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`btn-hole-white`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-sm text-primary-50">white</div>
                            </label>
                        </div>
                    </div>
                    <div></div>
                </div>
            </div>
         </div> -->
    </div>
</template>


<style lang="scss" scoped>
    input[type="radio"] + label span.checkbox-inner {
        @apply border-primary-50;

        &.pink {
            @apply border-pink-ka;
        }
    }
    input[type="radio"]:checked + label span.checkbox-inner {
        @apply bg-primary-50 border-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;

        &.pink {
            @apply border-pink-ka bg-pink-ka;
        }
    }
    .checkbox-inner {
        @apply flex items-center justify-center text-transparent border size-8 border-primary-50;
        background: transparent no-repeat center;

        &.pink {
            @apply border-pink-ka;
        }
    }
</style>
