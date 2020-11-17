<?php

 namespace Gorin\Container;
 use Psr\Container\ContainerInterface;
 use Gorin\Container\Exception\ContainerException;
 use Gorin\Container\Exception\NotFoundException;


 class Container implements ContainerInterface, \ArrayAccess {

   protected $entries = [];

   public function __construct (array $entries = []) {
     for ($i = 0; $i < count($entries); $i++) {
       $this->add(key($entries), array_shift($entries));
     }
   }

   public function get ($id) {
     if (!$this->has($id)) {
       throw new NotFoundException("No entry was found for the identifier '${id}'");
     }
     return $this->entries[$id];
   }

   public function has ($id) {
     return isset($this->entries[$id]);
   }

   public function set ($id, $value) {
     return $this->add($id, $value);
   }

   public function add (string $id, $value) {
     if ($this->has($id)) {
       throw new ContainerException("Entry for identifier '${id}' already exists");
     }
     $this->entries[$id] = $value;
     return $this;
   }

   public function unset ($id) {
     if ($this->has($id)) {
       unset($this->entries[$id]);
       return $this;
     } else {
       throw new NotFoundException("No entry was found for the identifier '${id}'");
     }
   }

   public function offsetSet ($offset, $value) {
     return $this->add($offset, $value);
   }

   public function offsetExists ($offset) {
     return $this->has($offset, $value);
   }

   public function offsetUnset ($offset) {
     return $this->unset($offset);
   }

   public function offsetGet ($offset) {
     return $this->get($offset);
   }

 }
