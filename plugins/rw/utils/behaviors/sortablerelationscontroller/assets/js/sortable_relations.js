const SortableListWidget = function () {

    this.relationManager = null;

    this.sortableOrders = [];

    this.relationname = null;

    this.init = function (relationManager) {
        this.relationManager = relationManager;
        this.createSortable();
        this.bindObserver();
    };

    this.createSortable = function () {
        const sortableList = this.relationManager.querySelector('table tbody');

        if (sortableList && !sortableList.querySelector('.no-data')) {
            this.sortableOrders = this.getSortableOrders(sortableList);
            this.relationName = this.getSortableRelationName(sortableList);

            Sortable.create(sortableList, {
                handle: ".sortable-drag-handler",
                animation: 100,
                onUpdate: (function () {
                    const sortableIds = this.getSortableIds(sortableList);

                    $(sortableList).request('onReorderRelation', {
                        data: {
                            sortableIds: sortableIds,
                            sortableOrders: this.sortableOrders,
                            relationName: this.relationName
                        }
                    });
                }).bind(this)
            });
        }
    };

    this.getSortableIds = function (sortableList) {
        const sortableHandlers = sortableList.querySelectorAll('.sortable-drag-handler');

        const sortableIds = [];
        for (let i = 0; i < sortableHandlers.length; i++) {
            const sortableId = sortableHandlers[i].dataset.sortableId;
            sortableIds.push(sortableId);
        }

        return sortableIds;
    };

    this.getSortableOrders = function (sortableList) {
        const sortableHandlers = sortableList.querySelectorAll('.sortable-drag-handler');

        const sortableOrders = [];
        for (let i = 0; i < sortableHandlers.length; i++) {
            const sortableOrder = sortableHandlers[i].dataset.sortableOrder;
            sortableOrders.push(sortableOrder);
        }

        return sortableOrders;
    };

    this.getSortableRelationName = function (sortableList) {
        const sortableHandler = sortableList.querySelector('.sortable-drag-handler');
        const relationName = sortableHandler.dataset.sortableRelation;

        return relationName;
    };

    this.bindObserver = function () {
        const callback = (function (mutationsList) {
            for (let mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    this.createSortable();
                }
            }
        }).bind(this);

        const observer = new MutationObserver(callback);

        observer.observe(this.relationManager, {
            childList: true
        });
    };
}

const SortableListWidgetManager = {

    init: function () {
        this.createSortables();
    },

    createSortables: function () {
        const relationManagers = document.querySelectorAll('.relation-manager');
        for (let i = 0; i < relationManagers.length; i++) {
            if (relationManagers[i].querySelector('.sortable-relations-manager'))
                new SortableListWidget().init(relationManagers[i]);
        }
    }
}

window.onload = function () {
    SortableListWidgetManager.init();
};
