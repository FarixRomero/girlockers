<?php

namespace App\Livewire\Traits;

trait HasOrderableItems
{
    use HasFlashMessages;
    /**
     * Move an item up in the order
     *
     * @param int $itemId
     * @return void
     */
    public function moveUp(int $itemId): void
    {
        $modelClass = $this->getOrderableModel();
        $item = $modelClass::findOrFail($itemId);

        $parentColumn = $this->getParentColumn();
        $parentId = $this->getParentId();

        // Find the previous item
        $previousItem = $modelClass::where($parentColumn, $parentId)
            ->where('order', '<', $item->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousItem) {
            $this->swapOrders($item, $previousItem);
            $this->flashSuccess("'{$item->title}' movido hacia arriba.");
            $this->reloadItems();
        }
    }

    /**
     * Move an item down in the order
     *
     * @param int $itemId
     * @return void
     */
    public function moveDown(int $itemId): void
    {
        $modelClass = $this->getOrderableModel();
        $item = $modelClass::findOrFail($itemId);

        $parentColumn = $this->getParentColumn();
        $parentId = $this->getParentId();

        // Find the next item
        $nextItem = $modelClass::where($parentColumn, $parentId)
            ->where('order', '>', $item->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextItem) {
            $this->swapOrders($item, $nextItem);
            $this->flashSuccess("'{$item->title}' movido hacia abajo.");
            $this->reloadItems();
        }
    }

    /**
     * Swap the order of two items
     *
     * @param \Illuminate\Database\Eloquent\Model $item1
     * @param \Illuminate\Database\Eloquent\Model $item2
     * @return void
     */
    protected function swapOrders($item1, $item2): void
    {
        $tempOrder = $item1->order;
        $item1->update(['order' => $item2->order]);
        $item2->update(['order' => $tempOrder]);
    }

    /**
     * Get the model class name for orderable items
     * Must be implemented by the component
     *
     * @return string
     */
    abstract protected function getOrderableModel(): string;

    /**
     * Get the parent column name (e.g., 'course_id', 'module_id')
     * Must be implemented by the component
     *
     * @return string
     */
    abstract protected function getParentColumn(): string;

    /**
     * Get the parent ID value
     * Must be implemented by the component
     *
     * @return int
     */
    abstract protected function getParentId(): int;

    /**
     * Reload the items after reordering
     * Must be implemented by the component
     *
     * @return void
     */
    abstract protected function reloadItems(): void;
}
