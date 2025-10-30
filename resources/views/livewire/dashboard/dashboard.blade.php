<x-layouts.main>
    <x-basecoat.page-header>
        <x-slot name="header">
            Dashboard
        </x-slot>
        <x-slot name="subHeader">
            Welcome to your dashboard
        </x-slot>
    </x-basecoat.page-header>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-basecoat.card header="Meeting Notes">
            <x-slot name="subHeader">
                <p>Transcript from the meeting with the client.</p>
            </x-slot>
            <section class="text-sm">
                <p>Client requested dashboard redesign with focus on mobile responsiveness.</p>
                <ol class="mt-4 flex list-decimal flex-col gap-2 pl-6">
                    <li>New analytics widgets for daily/weekly metrics</li>
                    <li>Simplified navigation menu</li>
                    <li>Dark mode support</li>
                    <li>Timeline: 6 weeks</li>
                    <li>Follow-up meeting scheduled for next Tuesday</li>
                </ol>
            </section>
            <x-slot name="footer">
                <div
                    class="flex -space-x-2 [&_img]:ring-card [&_img]:ring-2 [&_img]:grayscale [&_img]:size-8 [&_img]:shrink-0 [&_img]:object-cover [&_img]:rounded-full">
                    <img alt="@developerabdan" src="https://avatars.githubusercontent.com/u/42459820?s=40&v=4">
                    <img alt="@hunvreus" src="https://github.com/hunvreus.png">
                    <img alt="@shadcn" src="https://github.com/shadcn.png">
                    <img alt="@adamwathan" src="https://github.com/adamwathan.png">
                </div>
            </x-slot>
        </x-basecoat.card>
        <x-basecoat.card header="Accordion">
            <x-slot name="subHeader">
                <p>some basecoat component from basecoat</p>
            </x-slot>
            <x-basecoat.accordion>
                <x-basecoat.accordion.item title="are you gae?">
                    <p>test</p>
                </x-basecoat.accordion.item>
            </x-basecoat.accordion>
        </x-basecoat.card>
    </div>
</x-layouts.main>
