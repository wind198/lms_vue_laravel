import type { RouteRecord } from "vue-router";

class TreeNode {
  name: string;
  path: string;
  children: Record<string, TreeNode>;
  constructor(name: string, path: string) {
    this.name = name; // Route name or label
    this.path = path; // Full path for the route
    this.children = {}; // Child nodes
  }

  addChild(childNode: TreeNode) {
    this.children[childNode.name] = childNode;
  }

  toString() {
    let output = "";
    output +=
      `Node: ${this.name}, path: ${this.path}, children: ${this.children}` +
      "\n";
    Object.values(this.children).forEach(
      (i) => (output += i.toString() + "\n")
    );
  }
}

class Tree {
  root: TreeNode;
  children: Record<string, TreeNode> = {};

  constructor() {
    this.root = new TreeNode("root", "/"); // Root node
  }

  addRoute(route: RouteRecord) {
    const pathSegments = route.path.split("/").filter(Boolean); // Remove empty segments
    let currentNode = this.root;
    let segmentIndex = 0;

    while (true && segmentIndex < pathSegments.length) {
      let currentSegment = pathSegments[segmentIndex];
      const match = currentNode.children[currentSegment];
      if (match) {
        currentNode = match;
      } else {
        const createdNode = new TreeNode(currentSegment, route.path);

        currentNode.children[currentSegment] = createdNode;
        currentNode = createdNode;
      }
      segmentIndex++;
    }
  }

  displayTree() {
    console.log("Root: " + this.root.toString());
  }
}
